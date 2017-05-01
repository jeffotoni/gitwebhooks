<?php
/**
*
* @about 	project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor 	@jeffotoni
* @date 	25/04/2017
* @since    Version 0.1
* 
*/
	
// 
// 
// 

namespace web\src\Message\Logs;

//
// To handle the post coming from github
//

class ShowUp
{

	public function __construct() {


	}

	public function __ivoke() {

		print "__invoke()";
		exit;
	}

	public function ShowError() {


		//$method = $_SERVER['REQUEST_METHOD'];

		print "\n";

		print "I'm in Message()->Logs()->ShowUp()->". __METHOD__;

		print "\n";
	}
}