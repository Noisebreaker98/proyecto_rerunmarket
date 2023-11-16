<?php

class Foto {
    private $idFoto;
    private $idAnuncio;
    private $nombre_foto;

    public function __construct() {

    }
    

    /**
     * Get the value of idFoto
     */
    public function getIdFoto()
    {
        return $this->idFoto;
    }

    /**
     * Set the value of idFoto
     */
    public function setIdFoto($idFoto): self
    {
        $this->idFoto = $idFoto;

        return $this;
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
     * Get the value of nombre_foto
     */
    public function getNombreFoto()
    {
        return $this->nombre_foto;
    }

    /**
     * Set the value of nombre_foto
     */
    public function setNombreFoto($nombre_foto): self
    {
        $this->nombre_foto = $nombre_foto;

        return $this;
    }
}