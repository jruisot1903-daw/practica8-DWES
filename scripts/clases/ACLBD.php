<?php
class ACLBD {
    private $bd;

    public function __construct($servidor, $usuario, $contrasenia, $baseDatos) {
        $this->bd = new mysqli($servidor, $usuario, $contrasenia, $baseDatos);
        if ($this->bd->connect_errno) {
            die("Error de conexión con la ACL: " . $this->bd->connect_error);
        }
        $this->bd->set_charset("utf8");
    }

    // ------------------- MÉTODOS DE USUARIOS -------------------

    public function existeUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $res = $this->bd->query("SELECT * FROM acl_usuarios WHERE nick = '$nick'");
        return $res && $res->num_rows > 0;
    }

    public function insertarUsuario($nick, $nombre, $password, $idRol) {
        if ($this->existeUsuario($nick)) {
            return false;
        }
        $nick = $this->bd->real_escape_string($nick);
        $nombre = $this->bd->real_escape_string($nombre);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $idRol = (int)$idRol;

        $sql = "INSERT INTO acl_usuarios (nick, nombre, password, cod_acl_role, borrado)
                VALUES ('$nick', '$nombre', '$passwordHash', $idRol, 0)";
        return $this->bd->query($sql);
    }

    public function modificarNombre($nick, $nuevoNombre) {
        $nick = $this->bd->real_escape_string($nick);
        $nuevoNombre = $this->bd->real_escape_string($nuevoNombre);
        $sql = "UPDATE acl_usuarios SET nombre = '$nuevoNombre' WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    public function cambiarPassword($nick, $password) {
        $nick = $this->bd->real_escape_string($nick);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE acl_usuarios SET password = '$passwordHash' WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    public function borrarUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $sql = "UPDATE acl_usuarios SET borrado = 1 WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    public function asignarRol($nick, $idRol) {
        $nick = $this->bd->real_escape_string($nick);
        $idRol = (int)$idRol;
        $sql = "UPDATE acl_usuarios SET cod_acl_role = $idRol WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    // ------------------- MÉTODOS DE ROLES -------------------

    public function insertarRol($nombre, $permisos = []) {
        $nombre = $this->bd->real_escape_string($nombre);
        $campos = "nombre";
        $valores = "'$nombre'";

        for ($i = 1; $i <= 10; $i++) {
            $campos .= ", perm$i";
            $valores .= ", " . (isset($permisos[$i]) ? 1 : 0);
        }

        $sql = "INSERT INTO acl_roles ($campos) VALUES ($valores)";
        return $this->bd->query($sql);
    }

    public function listarRoles() {
        $roles = [];
        $res = $this->bd->query("SELECT * FROM acl_roles");
        if ($res) {
            while ($fila = $res->fetch_assoc()) {
                $roles[] = $fila;
            }
        }
        return $roles;
    }

    public function obtenerRolUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $res = $this->bd->query("SELECT r.* FROM acl_roles r 
                                 JOIN acl_usuarios u ON r.cod_acl_role = u.cod_acl_role
                                 WHERE u.nick = '$nick'");
        return $res ? $res->fetch_assoc() : null;
    }

    public function contarRoles() {
        $res = $this->bd->query("SELECT COUNT(*) AS total FROM acl_roles");
        $fila = $res->fetch_assoc();
        return (int)$fila['total'];
    }
}
