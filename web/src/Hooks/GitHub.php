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

    private static $show_msg_load = "";

    //
    //
    //

    private static $COMMITS = "";

    //
    //
    //

    private static $REMOTE_ADDR = "";
    
    //
    //
    //

    private static $HEAD_COMMIT_ID = "";
    
    private static $HEAD_COMMIT_MSG = "";

    private static $HEAD_COMMIT_AUTOR_NAME = "";
    
    private static $HEAD_COMMIT_AUTOR_USERNAME = "";
    
    private static $HEAD_COMMIT_AUTOR_EMAIL = "";


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

    private static $GITWEBHOOKS_AUTHENTICATION;

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

                    $msg = '{"msg":"HTTP header X-Hub-Signature is missing.!!"}';
                    self::$msgconcat .= $msg . PHP;
                    $this->LoadLog();
                    die($msg);

                } elseif (!extension_loaded('hash')) {

                    $msg = '{"msg":"Missing hash extension to check the secret code validity!"}';
                    self::$msgconcat .= $msg . PHP;
                    $this->LoadLog();
                    die($msg);
                }

                //
                //
                //

                list($hash_algos, $hash) = explode('=', self::$HTTP_X_HUB_SIGNATURE, 2) + array('', '');

                //
                //
                //

                if (!in_array($hash_algos, hash_algos(), true)) {

                    $msg = '{"msg":"Hash algorithm ' . $hash_algos . ' is not supported !!!"}';
                    self::$msgconcat .= $msg . PHP;
                    $this->LoadLog();
                    die($msg);
                }

                //
                //
                //

                if ($hash !== hash_hmac($hash_algos, self::$payload, GITHUB_SECRET)) {
                    
                    $msg = '{"msg":"Hook Secret does not match!!!"}';
                    self::$msgconcat .= $msg . PHP;
                    $this->LoadLog();
                    die($msg);

                } else {

                    //
                    //
                    //

                    self::SetDataWebHooks(self::$jsonDecode);

                    // ok
                    // 
                    // 

                    self::$msgconcat .= 'Connect sucess! Logged in!' . PHP_EOL;
                    self::$msgconcat .= "" . PHP_EOL;


                }

            } else {

                $msg = '{"msg":"Error payload failed, does not exist "}';
                self::$msgconcat .= $msg . PHP;

                $this->LoadLog();
                
                die($msg);

                
            }
            
            //
            //
            //

            return $this;

        } else {

            $msg = '{"msg":"You need GITHUB_SECRET which is in your setconfig.conf.php"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);
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

                self::$msgconcat .= "Mount Data payload success! " . PHP_EOL;
                self::$msgconcat .= "" . PHP_EOL;
                
                //
                //
                //

                self::SetDataWebHooks(self::$jsonDecode);


            } else {

                $msg = '{"msg":"Error Authentication failed! Your [Key] empty !!"}';
                self::$msgconcat .= $msg . PHP;

                $this->LoadLog();
                
                die($msg);
            }

        } else {


                $msg = '{"msg":"Error payload failed, does not exist!!"}';
                self::$msgconcat .= $msg . PHP;
                $this->LoadLog();
                die($msg);

            
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

            if (!self::$GITWEBHOOKS_AUTHENTICATION) {

                $msg = '{"msg":"HTTP header GitWebHooks-Authentication is missing."}';
                self::$msgconcat .= $msg . PHP;
                $this->LoadLog();
                die($msg);

            } elseif (!extension_loaded('hash')) {

                $msg = '{"msg":"Missing hash extension to check the secret code validity!!!"}';
                self::$msgconcat .= $msg . PHP;
                $this->LoadLog();
                die($msg);
            }

            //
            //
            //

            list($hash_algos, $hash) = explode('=', self::$GITWEBHOOKS_AUTHENTICATION, 2) + array('', '');

            //
            //
            //

            if($hash != GITWEBHOOKS_SECRET) {

                $msg = '{"msg":"GitWebHook Secret does not match!!!"}';
                self::$msgconcat .= $msg . PHP;
                $this->LoadLog();
                die($msg);

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

                self::$msgconcat .= 'connect sucess! Logged in!' . PHP_EOL;
                self::$msgconcat .= "" . PHP_EOL;

            }

            //
            //
            //

            return $this;

        } else {

            //
            //
            //
            $msg = '{"msg":"You need GITHUB_SECRET which is in your setconfig.conf.php!!"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);
        }
    }


    //
    //
    //

    private static function SetDataServer() 
    {

        self::$REMOTE_ADDR = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";
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
        
        self::$GITWEBHOOKS_AUTHENTICATION = isset($_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION']) ? $_SERVER['HTTP_GITWEBHOOKS_AUTHENTICATION'] : "";
    }


    //
    //
    //

    private static function SetDataWebHooks($json) 
    {


        self::$msgconcat .= '------------------------------------------------ SetDataWebHooks ------------------------------------------------' . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;
        //
        // Coming from github
        //

        self::$REF           = $json->ref;

        self::$msgconcat .= "Ref: " . self::$REF  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;
        

        //
        //
        //

        self::$REP_ID        = $json->repository->id;

        self::$msgconcat .= "Rep Id: " . self::$REP_ID  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        //
        // Coming from github
        //

        $rep_name       = $json->repository->name;

        self::$msgconcat .= "Rep Name: " . self::$rep_name  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

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


        self::$msgconcat .= "Branch: " . self::$BRANCH  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        self::$HEAD_COMMIT_ID               = $json->head_commit->id;

        self::$HEAD_COMMIT_MSG              = $json->head_commit->message;
        
        self::$HEAD_COMMIT_AUTOR_NAME       = $json->head_commit->author->name;

        self::$HEAD_COMMIT_AUTOR_USERNAME   = $json->head_commit->author->username;
        
        self::$HEAD_COMMIT_AUTOR_EMAIL      = $json->head_commit->author->email;



        self::$msgconcat .= "Commit Id: " . self::$HEAD_COMMIT_ID  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        self::$msgconcat .= "Commit message: " . self::$HEAD_COMMIT_MSG  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        self::$msgconcat .= "Autor name: " . self::$HEAD_COMMIT_AUTOR_NAME  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        self::$msgconcat .= "Autor username: " . self::$HEAD_COMMIT_AUTOR_USERNAME  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        self::$msgconcat .= "Autor email: " . self::$HEAD_COMMIT_AUTOR_EMAIL  . PHP_EOL;
        self::$msgconcat .= "" . PHP_EOL;

        //
        // Validating
        //

        if(!self::$REPOSITORY) {

            $msg = '{"msg":"Error repository empty!!"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);

        } else if(!self::$BRANCH) {

            $msg = '{"msg":"Error BRANCH empty!!"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);

        } else if(!self::$REP_ID) {

            $msg = '{"msg":"Error repository[name] empty!!"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);

        } else if(!self::$CONTENT_TYPE) {
    
            $msg = '{"msg":"Error CONTENT_TYPE empty!!"}';
            self::$msgconcat .= $msg . PHP;
            $this->LoadLog();
            die($msg);

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

            $msg = '{"msg":"Unsupported content type: ['.empty(self::$CONTENT_TYPE) ? " empty" : " [" . self::$CONTENT_TYPE . ']"}';
            self::$msgconcat .= $msg . PHP_EOL;
            $this->LoadLog();
            die($msg);
        }


        if(!self::$payload) {

            $msg = '{"msg":"Fatal error, payload not found!"}';
            self::$msgconcat .= $msg . PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;

            $this->LoadLog();
            
            die($msg);
        }

        //
        //
        //

        self::$jsonDecode = json_decode(self::$payload);

        if(is_object(self::$jsonDecode) && isset(self::$jsonDecode->ref) && self::$jsonDecode->ref) {

            self::$msgconcat .= "Mount Data jsonDecode success! " . PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;

        } else { 

            $msg = '{"msg":"Mount Data jsonDecode fatal error!"}';
            self::$msgconcat .= "" . $msg . PHP_EOL;
            
            $this->LoadLog();
            
            die($msg);
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

                    $msg = '{"msg":"Fatal error, event not allowed [' . self::$HTTP_X_GITHUB_EVENT . " !=" . $EVENT . ']"}';
                    self::$msgconcat .= $msg . PHP;

                    $this->LoadLog();

                    die($msg);
                }

                break;
                

             //
             //
             //

            default:

                    $msg = '{"msg":"Fatal error, event not Event not allowed [' . self::$HTTP_X_GITHUB_EVENT . ']"}';
                    self::$msgconcat .= $msg . PHP;

                    $this->LoadLog();
                    
                    die($msg);
              // code...
              break;
            }

        } else {


            $msg = '{"msg":"HTTP_X_GITHUB_EVENT empty , fatal error"}';
            self::$msgconcat .= $msg . PHP;

            $this->LoadLog();
            
            die($msg);
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

        $this->LoadLog();
        
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

    public function LoadLog()
    {

        //
        //
        //

        $IP = self::$REMOTE_ADDR;

        //
        //
        //

        $HTTP_USER_AGENT = self::$HTTP_USER_AGENT;

        //
        //
        //
        $msgtmp = PHP_EOL . "------------------------------------------------- Start GhitHub --------------------------------------------------" . PHP_EOL;
        $msgtmp .= date("Y-m-d [H:i]") . " [{$IP}] [{$HTTP_USER_AGENT}]" . PHP_EOL;
        $msgtmp .= "" . PHP_EOL;
        self::$show_msg_load = $msgtmp . self::$msgconcat . PHP_EOL;

        //
        //
        //

        if(!file_put_contents(PATH_LOG, self::$show_msg_load, FILE_APPEND)) {

            //
            //
            // 
            self::$msgconcat .= "Error:" . PHP_EOL;
            $msg = '{"msg":"Error writing log [' . PATH_LOG . ']"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

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
