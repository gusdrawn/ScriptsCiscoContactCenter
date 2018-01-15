<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
	Autor: Gustavo Guillen
	
	
	Primera Version de ART

*/
class Test extends CI_Controller {
	function __construct()    {
		parent::__construct();
		echo 'esto es una prueba';	
    }
	
	public function index()    {
	    echo 'esto es una prueba';
		$this->load->model('Awro');
		$query['row'] = $this->Awro->consultar();
		var_dump($query);
		echo "aqui termina todo";
		
	}
	
}