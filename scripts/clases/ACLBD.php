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

    // Comprobar si existe un usuario por nick
    public function existeUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $res = $this->bd->query("SELECT * FROM acl_usuarios WHERE nick = '$nick'");
        return $res && $res->num_rows > 0;
    }

    // Insertar usuario en ACL
    public function insertarUsuario($nick, $nombre, $password, $idRol) {
        if ($this->existeUsuario($nick)) {
            return false; // Ya existe
        }
        $nick = $this->bd->real_escape_string($nick);
        $nombre = $this->bd->real_escape_string($nombre);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $idRol = (int)$idRol;

        $sql = "INSERT INTO acl_usuarios (nick, nombre, contrasenia, cod_acl_role, borrado)
                VALUES ('$nick', '$nombre', '$passwordHash', $idRol, 0)";
        return $this->bd->query($sql);
    }

    // Modificar nombre de usuario
    public function modificarNombre($nick, $nuevoNombre) {
        $nick = $this->bd->real_escape_string($nick);
        $nuevoNombre = $this->bd->real_escape_string($nuevoNombre);
        $sql = "UPDATE acl_usuarios SET nombre = '$nuevoNombre' WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    // Cambiar contraseña
    public function cambiarPassword($nick, $password) {
        $nick = $this->bd->real_escape_string($nick);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE acl_usuarios SET contrasenia = '$passwordHash' WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    // Borrado lógico de usuario
    public function borrarUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $sql = "UPDATE acl_usuarios SET borrado = 1 WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    // Asignar rol a usuario
    public function asignarRol($nick, $idRol) {
        $nick = $this->bd->real_escape_string($nick);
        $idRol = (int)$idRol;
        $sql = "UPDATE acl_usuarios SET cod_acl_role = $idRol WHERE nick = '$nick'";
        return $this->bd->query($sql);
    }

    public function esValido($nick, $contrasenia) {
    $nick = $this->bd->real_escape_string($nick);
    $sql = "SELECT contrasenia FROM acl_usuarios WHERE nick = '$nick' AND borrado = 0";
    $res = $this->bd->query($sql);

    if ($res && $res->num_rows > 0) {
        $fila = $res->fetch_assoc();
        return password_verify($contrasenia, $fila['contrasenia']);
    }
    return false;
}

    // ------------------- MÉTODOS DE ROLES -------------------

    // Insertar rol
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

    // Listar roles
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

    // Obtener rol de un usuario
    public function obtenerRolUsuario($nick) {
        $nick = $this->bd->real_escape_string($nick);
        $res = $this->bd->query("SELECT r.* FROM acl_roles r 
                                 JOIN acl_usuarios u ON r.cod_acl_role = u.cod_acl_role
                                 WHERE u.nick = '$nick'");
        return $res ? $res->fetch_assoc() : null;
    }

    // Contar roles
    public function contarRoles() {
        $res = $this->bd->query("SELECT COUNT(*) AS total FROM acl_roles");
        $fila = $res->fetch_assoc();
        return (int)$fila['total'];
    }
}
