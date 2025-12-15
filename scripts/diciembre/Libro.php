<?php

class Libro{
    private string $_nombre;
    private string $_autor;

    private array $_otras = array();

    private array $_libros = [];

    //CONSTRUCTOR
    function __construct(string $nombre, string $autor, ... $_otras)
    {
        $this-> _nombre = $nombre;
        $this-> _autor = $autor;
    }

    //METODOS

    function anaiadirlibro(string $nombre, string $autor){

    }

    function dameLibros(){
    }
}