<?php namespace App\Controllers;


class Home extends BaseController
{
	public function index()
	{
        echo view("autorizacion");
	}

    // Funcion del inicio de sesion de un usuario
    public function login()
    {
        $correo = $_POST["correo"];
        $contra = $_POST["contra"];

        $fusuario = new Usuarios();
        
        $usuario = $fusuario->traerUsuario($correo, $contra);
        if (is_null($usuario))
            return redirect()->to(base_url()."/index.php/home/iniciar"); // No existe el usuario
        //print_r($usuario); die;
        // Iniciar sesion
        $usuario = $usuario[0];
        $_SESSION["idUsuario"]  = $usuario["idUsuario"];
        $_SESSION["nombres"]    = $usuario["nombres"];
        $_SESSION["dni"]        = $usuario["dni"];
        $_SESSION["id_perfil"]  = $usuario["id_perfil"];
        $_SESSION["id_cliente"] = $usuario["id_cliente"];
        $_SESSION["correo"]     = $usuario["correo"];
        $_SESSION["rutaFoto"]   = $usuario["rutaFoto"];
        // Necesarios
        // Edite estas 3 variables globales de acuerdo a su disposicion
        $_SESSION["auth"] = "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VMaHJqbVR2b2cyS0hMZ2l4b0s4YjZjcHR0dS8wZFRXOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlL3BKUmZVVlhYc1E0MW9TUURnUHUzNDB6VU42TlZSbQ==";
        $_SESSION["tam"] = -266; // Para eliminar lo que trae cURL
        $_SESSION["ruta"] = "/var/www/html/colibri/public/"; // Cambio de la ruta para las fotos
        //$_SESSION["auth"] = "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VtdWd2bEtkUFY0UmViV1VjUVdlUHI2dUc0cWRPbEZhOm8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlS3JZV0NKeEtUWGRvQklraDIzOXp3bXNPMmZYSzVUTw==";
        //$_SESSION["tam"] = -274; // Para eliminar lo que trae cURL para el servidor        
        //$_SESSION["ruta"] = "/public/"; // Cambio de la ruta para las fotos para el servidor    

        $perfiles = new \App\Models\ModeloPerfiles();
        $perfil = $perfiles->traerPorId($usuario["id_perfil"], $usuario["id_cliente"]);
        if (empty($perfil))
            return redirect()->to(base_url()."/index.php/home/iniciar"); // Su perfil esta eliminado
        $perfil = $perfil[0];
        $_SESSION["perfil"] = $perfil["perfil"];

        //var_dump($_SESSION);die;
        return redirect()->to(base_url()."/index.php/casa");

    }

    public function iniciar()
    {
        echo view("login");
    }

	//--------------------------------------------------------------------

}
