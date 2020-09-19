<?php namespace App\Controllers;


class Home extends BaseController
{
	public function index()
	{
        $bd = \Config\Database::connect();
        if ($bd)
            return view("estructura");
		return view('welcome_message');
	}

	//--------------------------------------------------------------------

}
