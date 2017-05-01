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

namespace web\src\Handlers;


//
// To handle the post coming from github
//

class RequestResponse
{

	public function __construct() {


	}

	public function GetRequest() {



		print "\n";

		print "I'm in Handlers->RequestResponse()->" . __METHOD__;

		print "\n";
	}
}