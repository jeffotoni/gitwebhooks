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

namespace web\src\Hooks;


//
// To handle the post coming from github
//

class GitHub
{
    

    //
    //
    //

    private static $CONTENT_TYPE;

    //
    //
    //

    public static $REPOSITORY;

    //
    //
    //
    
    public static $BRANCH;

    //
    //
    //
    
    private static $REF;

    //
    //
    //
    
    private static $REP_ID;

    //
    //
    //
    
    private static $HTTP_X_GITHUB_EVENT;

    //
    //
    //
    
    private static $HTTP_USER_AGENT;
    
    //
    //
    //
    
    private static $HTTP_X_HUB_SIGNATURE;
    
    //
    //
    //

    private static $GITWEBHOOS_AUTHENTICATION;

    //
    //
    //

    private static $msgconcat = "";

    //
    //
    //

    private static $payload = null;

    //
    //
    //

    private static $jsonDecode = null;


    //
    //
    //

    public function AuthenticateSecretToken() 
    {

        //
        //
        //

        if (GITHUB_SECRET !== null) {

            self::SetDataServer();
            //
            //
            //

            self::GetPlayLoad();

            //
            //
            //

            if(self::$payload) {

                //
                //

                if (!self::$HTTP_X_HUB_SIGNATURE) {

                    die("\nHTTP header 'X-Hub-Signature' is missing.\n");

                } elseif (!extension_loaded('hash')) {

                    die("\nMissing 'hash' extension to check the secret code validity.\n");
                }

                //
                //
                //

                list($hash_algos, $hash) = explode('=', self::$HTTP_X_HUB_SIGNATURE, 2) + array('', '');

                //
                //
                //

                if (!in_array($hash_algos, hash_algos(), true)) {

                    die("\nHash algorithm '$hash_algos' is not supported !!!\n");
                }

                //
                //
                //

                if ($hash !== hash_hmac($hash_algos, self::$payload, GITHUB_SECRET)) {
                    
                    die("\nHook Secret does not match!!!\n");

                } else {

                    //
                    //
                    //

                    self::SetDataWebHooks(self::$jsonDecode);

                    // ok
                    // 
                    // 

                    self::$msgconcat .= 'connect sucess! Logged in!';
                }

            } else {

                die("\nError payload failed, does not exist !!!\n");
            }
            
            //
            //
            //

            return $this;

        } else {

            die("\nYou need GITHUB_SECRET which is in your setconfig.conf.php\n");
        }
    }

    //
    //
    //

    public function AuthenticateSecretKey() 
    {

        self::SetDataServer();
        //
        //
        //

        self::GetPlayLoad();

        //
        //
        //

        if(self::$payload) {

            //
            //
            //

            if(isset($_GET['key']) && $_GET['key'] == KEY) {


                //
                //
                //

                self::$msgconcat .= "Mount Data payload success! ";    

                //
                //
                //

                self::SetDataWebHooks(self::$jsonDecode);


            } else {

                die("\nError Authentication failed! Your [Key] empty !!\n");
            }

        } else {

            die("\nError payload failed, does not exist !!!\n");
        }
        
        //
        //
        //

        return $this;
    }


    //
    //
    //

    //
    //
    //

    public function AuthenticateMd5() 
    {


        //
        //
        //

        if (GITWEBHOOKS_SECRET !== null) {

            //
            //
            //

            self::SetDataServer();

            //
            //
            //

            if (!self::$GITWEBHOOS_AUTHENTICATION) {

                die("HTTP header 'GitWebHooks-Authentication' is missing.");

            } elseif (!extension_loaded('hash')) {

                die("Missing 'hash' extension to check the secret code validity!!!");
            }

            //
            //
            //

            list($hash_algos, $hash) = explode('=', self::$GITWEBHOOS_AUTHENTICATION, 2) + array('', '');

            //
            //
            //

            if($hash != GITWEBHOOKS_SECRET) {

                die('GitWebHook Secret does not match!!!');

            } else {

                // ok
                // 
                // 

                self::GetPlayLoad();

                //
                //
                //

                self::SetDataWebHooks(self::$jsonDecode);

                //
                //
                //

                self::$msgconcat .= 'connect sucess! Logged in!';
            }

            //
            //
            //

            return $this;

        } else {

            //
            //
            //

            die("\nYou need GITHUB_SECRET which is in your setconfig.conf.php\n");
        }
    }


    //
    //
    //

    private static function SetDataServer() 
    {

        //
        //
        //
        self::$CONTENT_TYPE = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] ? $_SERVER['CONTENT_TYPE'] : "";
        // 
        // 
        // 

        self::$HTTP_X_GITHUB_EVENT     = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : "";

        // 
        // You can prevent by agent the shipments
        // 

        self::$HTTP_USER_AGENT         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        // 
        // 
        // 

        self::$HTTP_X_HUB_SIGNATURE = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : "";

        //
        //
        //
        
        self::$GITWEBHOOS_AUTHENTICATION = isset($_SERVER['HTTP_GITWEBHOOS_AUTHENTICATION']) ? $_SERVER['HTTP_GITWEBHOOS_AUTHENTICATION'] : "";
    }


    //
    //
    //

    private static function SetDataWebHooks($json) 
    {


        //
        // Coming from github
        //

        self::$REF           = $json->ref;

        //
        //
        //

        self::$REP_ID        = $json->repository->id;

        //
        // Coming from github
        //

        $rep_name       = $json->repository->name;

        // 
        // Coming from github
        // 

        $tmp = explode("/", self::$REF);
        $ref_branch = end($tmp);

        // 
        // 
        // 

        self::$REPOSITORY  = $rep_name;

        // 
        // 
        // 

        self::$BRANCH     = $ref_branch;


        //
        // Validating
        //

        if(!self::$REPOSITORY) {

            die("\nError repository empty!!!\n");

        } else if(!self::$BRANCH) {

            die("\nError BRANCH empty!!!\n");

        } else if(!self::$REP_ID) {

            die("\nError repository[name] empty!!!\n");

        } else if(!self::$CONTENT_TYPE) {
        
            die("\nError CONTENT_TYPE empty!!!\n");

        }

    }


    //
    //
    //

    private static function GetPlayLoad() 
    {

        
        //
        //
        //
        //

        switch (self::$CONTENT_TYPE) {

         //
         //
         //

        case 'application/json':
                
            //
            //
            //

            self::$payload = file_get_contents('php://input');


            break;
            

        case 'application/x-www-form-urlencoded':

            //
            //
            //

            self::$payload = isset($_POST['payload']) ? $_POST['payload'] : "";

            break;

        default:
            die("\nUnsupported content type: [".empty(self::$CONTENT_TYPE) ? " empty" : " [" . self::$CONTENT_TYPE . "] \n");
        }


        if(!self::$payload) {

            self::$msgconcat .= " Fatal error, payload not found!";
            
            die("\n".self::$msgconcat."\n");
        }

        //
        //
        //

        self::$jsonDecode = json_decode(self::$payload);

        if(is_object(self::$jsonDecode) && isset(self::$jsonDecode->ref) && self::$jsonDecode->ref) {

            self::$msgconcat .= "Mount Data jsonDecode success! ";    

        } else { 

            self::$msgconcat .= "Mount Data jsonDecode fatal error!";
            die("\n".self::$msgconcat."\n");
        }

    }

    //
    //
    //

    public function Event($EVENT) 
    {

        //
        //
        //

        $EVENT = trim(strtolower($EVENT));

        //
        //
        //

        if(self::$HTTP_X_GITHUB_EVENT) {

            //
            //
            //

            switch (self::$HTTP_X_GITHUB_EVENT) {

             //
             //
             //

            case 'push':
                    
                if($EVENT == self::$HTTP_X_GITHUB_EVENT) {

                    // code
                    return $this;

                } else {

                    self::$msgconcat .= "Fatal error, event not allowed [" . self::$HTTP_X_GITHUB_EVENT . " != {$EVENT}]";
                    die("\n".self::$msgconcat."\n");
                }

                break;
                

             //
             //
             //

            default:

                self::$msgconcat .= "Fatal error, event not Event not allowed [$HTTP_X_GITHUB_EVENT]";
                die("\n".self::$msgconcat."\n");

              // code...
              break;
            }

        } else {

            self::$msgconcat .= "HTTP_X_GITHUB_EVENT empty , fatal error!";
            die("\n".self::$msgconcat."\n");

        }
    }

    //
    //
    //

    public function WScript($api) 
    {

        //
        //
        //

        if(is_object($api)) {
            
            //
            //
            //

            return ($api->WScript());
        }
    }

    //
    //
    //

    public function TestClass() 
    {

        print "\n\n";
        
        print "I'm in Hooks()->GitHub()->". __METHOD__;

        print "\n";

        //return md5(uniqid(rand(), true));
    }
}
