<?php namespace App\Controllers;


class Home extends BaseController
{
	public function index()
	{
        return view("login");
	}

    // Funcion del inicio de sesion de un usuario
    public function login()
    {
        $correo = $_POST["correo"];
        $contra = $_POST["contra"];

        $fusuario = new Usuarios();
        
        $usuario = $fusuario->traerUsuario($correo, $contra);
        if (is_null($usuario))
            return redirect()->to(base_url()); // No existe el usuario
        //print_r($usuario); die;
        // Iniciar sesion
        $usuario = $usuario[0];
        $_SESSION["nombres"]    = $usuario["nombres"];
        $_SESSION["dni"]        = $usuario["dni"];
        $_SESSION["id_perfil"]  = $usuario["id_perfil"];
        $_SESSION["id_cliente"] = $usuario["id_cliente"];
        $_SESSION["correo"]     = $usuario["correo"];
        $_SESSION["rutaFoto"]   = $usuario["rutaFoto"];
        // Necesarios
        // Edite estas 3 variables globales de acuerdo a su disposicion
        $_SESSION["auth"] = "Authorization: Basic YTJhYTA3YWRmaGRmcmV4ZmhnZGZoZGZlcnR0Z2VoRlJEWFhwOFRtSVhVcTE3dnVSejUxbk81cU1UeEd5Om8yYW8wN29kZmhkZnJleGZoZ2RmaGRmZXJ0dGdlcHJyNEFxR0JiRWwzVS5lenNuUXlnUUk2THhKaGcxQw==";
        $_SESSION["tam"] = -274; // Para eliminar lo que trae cURL
        $_SESSION["ruta"] = "C:\\xampp\\htdocs\\colibri\\public\\"; // Cambio de la ruta para las fotos

        $perfiles = new \App\Models\ModeloPerfiles();
        $perfil = $perfiles->traerPorId($usuario["id_perfil"], $usuario["id_cliente"]);
        if (empty($perfil))
            return redirect()->to(base_url()); // Su perfil esta eliminado
        $perfil = $perfil[0];
        $_SESSION["perfil"] = $perfil["perfil"];

        return redirect()->to(base_url()."/index.php/casa");

    }

    public function autorizacion()
    {
        echo view("autorizacion");
    }

	//--------------------------------------------------------------------

}
