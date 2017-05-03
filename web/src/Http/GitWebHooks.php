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

class GitWebHooks
{
    
    private static $show_msg_load = "";
    
    //
    //
    //

    private static $msgconcat = "";

    //
    //
    //

    private static $GITWEBHOOKS_AUTHENTICATION;


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

    private static $GITWEBHOOKS_BRANCH;

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

            if (!self::$GITWEBHOOKS_AUTHENTICATION) {

                die("HTTP header 'GitWebHooks-Authentication' is missing.");

            } elseif (!extension_loaded('hash')) {

                die("Missing 'hash' extension to check the secret code validity!!!");
            }

            //
            //
            //

            list($hash_algos, $hash) = explode('=', self::$GITWEBHOOKS_AUTHENTICATION, 2) + array('', '');

            //
            //
            //

            if($hash != GITWEBHOOKS_SECRET) {

                //
                //
                // 
                
                self::$msgconcat .= "------------------------------ Error ------------------------------" . PHP_EOL;
                $msg = '{"msg":"GitWebHook Secret does not match!!!"}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "".PHP_EOL;

                $this->LoadLog();

                die($msg);

            } else {

                // ok
                // 
                // 

                self::$msgconcat .= 'connect sucess! Logged in!';
                self::$msgconcat .= "".PHP_EOL;
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

    public function AuthenticateSha1() 
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

            if (!self::$GITWEBHOOKS_AUTHENTICATION) {

                die("HTTP header 'X-Hub-Signature' is missing.");

            } elseif (!extension_loaded('hash')) {

                die("Missing 'hash' extension to check the secret code validity.");
            }

            //
            //
            //

            list($hash_algos, $hash) = explode('=', self::$GITWEBHOOKS_AUTHENTICATION, 2) + array('', '');

            //
            //
            //

            if (!in_array($hash_algos, hash_algos(), true)) {

                die("Hash algorithm '$hash_algos' is not supported !!!");
            }

            //
            //
            //

            if ($hash !== hash_hmac($hash_algos, file_get_contents("php://input"), GITHUB_SECRET)) {
                
                die('Hook Secret does not match!!!');

            } else {

                // ok
                // 
                // 

                self::$msgconcat .= 'connect sucess! Logged in!';
                self::$msgconcat .= "".PHP_EOL;
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
        //SERVER_ADDR
        //
        
        //
        //
        //
        self::$CONTENT_TYPE = isset($_SERVER['CONTENT_TYPE']) && $_SERVER['CONTENT_TYPE'] ? $_SERVER['CONTENT_TYPE'] : "";

        // 
        // You can prevent by agent the shipments
        // 

        self::$HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        // 
        // 
        // 

        self::$GITWEBHOOKS_AUTHENTICATION = isset($_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION']) ? $_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION'] : "";


        //
        //
        //

        self::$GITWEBHOOKS_BRANCH = isset($_SERVER['HTTP_GITWEBHOOKS_BRANCH']) ? $_SERVER['HTTP_GITWEBHOOKS_BRANCH'] : "";
        
    }

    public function LoadLog()
    {

        //
        //
        //

        $IP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

        //
        //
        //

        $HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        //
        //
        //

        self::$show_msg_load = date("Y-m-d [H:i]") . " [{$IP}] [{$HTTP_USER_AGENT}]" . PHP_EOL . self::$msgconcat . PHP_EOL;

        //
        //
        //

        if(!file_put_contents(PATH_LOG, self::$show_msg_load, FILE_APPEND)) {

            //
            //
            // 
            self::$msgconcat .= "------------------------------ Error ------------------------------" . PHP_EOL;
            $msg = '{"msg":"Error writing log [' . PATH_LOG . ']"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            //$this->LoadLog();

            //
            //
            //
            
            die($msg);
        }

        //
        //
        //

        return $this;
    }
}
