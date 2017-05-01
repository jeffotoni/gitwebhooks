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

class Response
{

    //
    //
    //

    public function __construct() 
    {


    }

    //
    //
    //

    public function WriteJson($msg) 
    {

        
        print json_encode($msg);
    }

    //
    //
    //

    public function Write($msg) 
    {

        print "\n";
        print ($msg);
        print "\n";
    }

    //
    //
    //

    public function getBody() 
    {

    
        print "I'm in Http()->Response()->". __METHOD__;

        return $this;
    }
}