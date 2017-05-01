<?php
/**
*
* @about     project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor     @jeffotoni
* @date     25/04/2017
* @since    Version 0.1
*/
    
// 
// 
// 

namespace web\src\Http;


//
// To handle the post coming from github
//

class Request
{

    //
    //
    //

    private $parameters_url = null;

    //
    //
    //

    public function __construct($parameters="") 
    {

        //get parameters
        if($parameters) {
            self::$parameters_url = $parameters; 
        }

    }

    public function getAttribute() 
    {


        //$method = $_SERVER['REQUEST_METHOD'];

        print "\n";

        print "I'm in Http()->Request()->". __METHOD__;

        print "\n";
    }
}