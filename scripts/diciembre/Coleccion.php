<?php
include_once(dirname(__FILE__) . "/../librerias/validacion.php");
class Coleccion{
     public  $TEMATICAS = ["cienciaficcion" => 10, "terror" => 20, "policiaco" => 30,"comedia" => 40];
     
     private string $_nombre;
     private string $_fecha_alta;
     private int $_tematica;
     private string $_tematicaDes; 
     
    //CONTRUCTOR

    function __construct(string $_nombre, string $_fecha_alta, int $_tematica)
    {
        $this-> _nombre = $_nombre; 
        $this-> _fecha_alta = $_fecha_alta;
        $this-> _tematica = $_tematica;
    }



     //GETTERS AND SETTERS

    public function get_nombre(){
        return $this->_nombre;
    }

    public function set_nombre($_nombre){
        if(validaCadena($_nombre,40,"Pepe")){
            $this-> _nombre = $_nombre;
        }
    }

    public function get_fechaAlta(){
        return $this-> _fecha_alta;
    }

    public function set_fechaAlta($_fecha_alta){
        $fechaHoy = date("d/m/y");

        if(!($fechaHoy > $_fecha_alta)){

            if(validaFecha($_fecha_alta,"1/10/2025")){
            $this-> _fecha_alta = $_fecha_alta;
        }
        }
    }


    public function get_tematica(){
        return $this-> _tematica;
    }

    public function set_tematica($_tematica){
        $this->_tematica = $_tematica;

          return $this;
    }
     
     public function get_tematicaDes($TEMATICAS)
     {
        foreach($TEMATICAS as $indice => $valor){
            $this-> _tematicaDes = $indice.":".$valor;
        }
     }


     function __toString()
     {
        return "Colección ".$this-> _nombre." añadida el ". $this-> _fecha_alta." de tématica <br>";
        //Si le pongo el tematicasDes me da fallo , de la forma en el que lo tengo por lo menos ves que me sale por pantalla las colecciones que tenga 
        // return "Colección ".$this-> _nombre." añadida el ". $this-> _fecha_alta." de tématica".$this->_tematicaDes." <br>";

     }
}