<?php namespace App\Controllers;


class Home extends BaseController
{
	public function index()
	{
        $bd = \Config\Database::connect();
        if ($bd)
            return "Base de datos conectado";
		return view('welcome_message');
	}

	//--------------------------------------------------------------------

}
