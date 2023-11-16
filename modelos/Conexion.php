<?php 
//Clase que realiza la conexión a BD 
class Conexion {
    private $conexion;

    //Constructor que se ejecutará automáticamente cuando creemos un nuevo objeto de la clase DATABASE
    //Se le pasan los datos de conexion a la BD en la instanciacion del objeto
    public function __construct($usuario,$contrasena='',$host, $base_de_datos) {
        $this->conexion = new mysqli($host, $usuario, $contrasena, $base_de_datos);

        if ($this->conexion->connect_error) { //Si falla la conexión, se muestra un error y para la ejecución del programa
            die("Error de conexión: " . $this->conexion->connect_error);
        }
    }

    public function getConexion() { //Getter para obtener la conexión a BD
        return $this->conexion;
    }
}

?>