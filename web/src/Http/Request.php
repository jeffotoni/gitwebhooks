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

    private static $GITWEBHOOS_AUTHENTICATION;


    //
    //
    //

    private static $HTTP_USER_AGENT;

    //
    //
    //

    private static $CONTENT_TYPE;

    //
    //
    //

    private static $GITWEBHOOS_BRANCH;

    //
    //
    //

    private static $GITWEBHOOKS_GITUSER;

    //
    //
    //

    private static $name = "";
        
    //
    //
    //
    private static $value = "";

    //
    //
    //

    private $parameters_url = null;

    //
    //
    //

    public function __construct($name="", $value="") 
    {

        self::SetEnvironment();
        //get parameters
        if($name && $value) {

            self::$name  = $name;
            self::$value = $value;
        }
    }


    //
    //
    //

    public function GetName() 
    {

        if(self::$value){

            return(self::$value);
            
        } else {

            die('"msg":"Value of name has not been set!"');
        }
    }

    //
    //
    //

    public function GitUser() 
    {

        if(self::$GITWEBHOOKS_GITUSER) {

            return(self::$GITWEBHOOKS_GITUSER);
            
        } else {

            die('"msg":"Value of GitWebHooks-GitUser has not been set!"');
            
        }

    }

    //
    //
    //

    public function GetBranch() 
    {

        if(self::$GITWEBHOOS_BRANCH) {

            return(self::$GITWEBHOOS_BRANCH);
            
        } else {

            die('"msg":"Value of GitWebHooks-Branch has not been set!"');
            
        }
    }

    //
    //
    //

    public function GetContenType() 
    {

         if(self::$CONTENT_TYPE) {

            return(self::$CONTENT_TYPE);
            
        } else {

            die('"msg":"Value of Content-Type has not been set!"');
            
        }
    }

    //
    //
    //

    private static function SetEnvironment() {
                
        //
        //
        //

        self::$CONTENT_TYPE = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] ? $_SERVER['CONTENT_TYPE'] : "";

        // 
        // You can prevent by agent the shipments
        // 

        self::$HTTP_USER_AGENT         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        // 
        // 
        // 

        self::$GITWEBHOOS_AUTHENTICATION = isset($_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION']) ? $_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION'] : "";


        //
        //
        //

        self::$GITWEBHOOS_BRANCH = isset($_SERVER['HTTP_GITWEBHOOKS_BRANCH']) ? $_SERVER['HTTP_GITWEBHOOKS_BRANCH'] : "";

        //
        //
        //

        self::$GITWEBHOOKS_GITUSER = isset($_SERVER['HTTP_GITWEBHOOKS_GITUSER']) ? $_SERVER['HTTP_GITWEBHOOKS_GITUSER'] : "";
    }

    //
    //
    //

    public function getAttribute() 
    {


        //$method = $_SERVER['REQUEST_METHOD'];

        print "\n";

        print "I'm in Http()->Request()->". __METHOD__;

        print "\n";
    }
}