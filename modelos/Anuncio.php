<?php

class Anuncio {
    private $idAnuncio;
    private $idUsuario;
    private $titulo;
    private $descripcion;
    private $precio;
    private $foto;
    private $fecha_creacion;

    public function __construct() {

    }

    /**
     * Get the value of idAnuncio
     */
    public function getIdAnuncio()
    {
        return $this->idAnuncio;
    }

    /**
     * Set the value of idAnuncio
     */
    public function setIdAnuncio($idAnuncio): self
    {
        $this->idAnuncio = $idAnuncio;

        return $this;
    }

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     */
    public function setIdUsuario($idUsuario): self
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of titulo
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set the value of titulo
     */
    public function setTitulo($titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get the value of descripcion
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set the value of descripcion
     */
    public function setDescripcion($descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get the value of precio
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * Set the value of precio
     */
    public function setPrecio($precio): self
    {
        $this->precio = $precio;

        return $this;
    }

    /**
     * Get the value of foto
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set the value of foto
     */
    public function setFoto($foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get the value of fecha_creacion
     */
    public function getFechaCreacion()
    {
        return $this->fecha_creacion;
    }

    /**
     * Set the value of fecha_creacion
     */
    public function setFechaCreacion($fecha_creacion): self
    {
        $this->fecha_creacion = $fecha_creacion;

        return $this;
    }

}