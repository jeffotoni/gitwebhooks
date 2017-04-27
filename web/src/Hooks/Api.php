<?php
/***********

 ▄▄▄██▀▀▀▓█████   █████▒ █████▒▒█████  ▄▄▄█████▓ ▒█████   ███▄    █  ██▓
   ▒██   ▓█   ▀ ▓██   ▒▓██   ▒▒██▒  ██▒▓  ██▒ ▓▒▒██▒  ██▒ ██ ▀█   █ ▓██▒
   ░██   ▒███   ▒████ ░▒████ ░▒██░  ██▒▒ ▓██░ ▒░▒██░  ██▒▓██  ▀█ ██▒▒██▒
▓██▄██▓  ▒▓█  ▄ ░▓█▒  ░░▓█▒  ░▒██   ██░░ ▓██▓ ░ ▒██   ██░▓██▒  ▐▌██▒░██░
 ▓███▒   ░▒████▒░▒█░   ░▒█░   ░ ████▓▒░  ▒██▒ ░ ░ ████▓▒░▒██░   ▓██░░██░
 ▒▓▒▒░   ░░ ▒░ ░ ▒ ░    ▒ ░   ░ ▒░▒░▒░   ▒ ░░   ░ ▒░▒░▒░ ░ ▒░   ▒ ▒ ░▓
 ▒ ░▒░    ░ ░  ░ ░      ░       ░ ▒ ▒░     ░      ░ ▒ ▒░ ░ ░░   ░ ▒░ ▒ ░
 ░ ░ ░      ░    ░ ░    ░ ░   ░ ░ ░ ▒    ░      ░ ░ ░ ▒     ░   ░ ░  ▒ ░
 ░   ░      ░  ░                  ░ ░               ░ ░           ░  ░

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
// 

namespace web\src\Hooks;

//
//
//

class Api
{

    // 
    // 
    // 

    const NameSpaceApi = "web\src\Hooks\\";

    //
    //
    //

    const NameSpaceCollect = [

    "Api" => "web\src\Hooks\\",

    ];

    // 
    // 
    // 

    private static $_REGIS = array();

    // 
    // 
    // 

    private static $nomeNameSpace;

    // 
    // 
    // 

    private static $PATH_INSTANCE = "";

    // 
    // 
    // 

    private static $CLASS_INSTANCE = "";

    // 
    // 
    // 

    public function __construct($instance=null)
    {
        

        // 
        // 
        // 

        if(!defined("PATH_LOCAL_API")) {

            define("PATH_LOCAL_API", getcwd() . "/" . trim(str_replace("\\", "/", self::NameSpaceCollect["Api"]), "/")); 
        }

        // 
        // 
        // 

        self::SAutoLoaderPath();

        // 
        // 
        // 

        spl_autoload_register(array( $this, 's3AutoLoaderNsp' ));

    }


    // 
    // 
    // 

    private static function SAutoLoaderPath()
    {

        // 
        // 
        // 

        chdir(dirname(__FILE__));

        // 
        // 
        // 

        $realPath = realPath("");

        // 
        // 
        // 

        self::$PATH_INSTANCE = $realPath;


        // 
        // 
        // 

        self::$CLASS_INSTANCE = str_replace(PATH_LOCAL_API, "", self::$PATH_INSTANCE);

        // 
        // 
        // 

        if(!is_dir(self::$PATH_INSTANCE)) { 

            exit("\n\nnot path!!\n");
        }

    }

    
    /**
    * Metodo ser carregado dinamicamente
    *
    * @param unknown_type $_CLASS
    */

    private function s3AutoLoaderNsp($_CLASS)
    {

        // 
        // 
        // 

        $_CLASS_CLEAN = explode("\\", $_CLASS); 

        // 
        // 
        // 

        $_CLASS_CLEAN = trim(end($_CLASS_CLEAN), "/");

        // 
        // 
        // 

        $PATH_CLASS = PATH_LOCAL_API."/{$_CLASS_CLEAN}";


        // 
        // 
        // 

        $PATH_CLASS = str_replace(array("\\"), array("/"), $PATH_CLASS);


        // 
        // 
        // 

        $PATH_CLASS.=".php";

        // 
        // 
        // 

        if(is_file($PATH_CLASS)) {
            
            include $PATH_CLASS;    

        } else { 

            exit("\n\nnot file in path [{$PATH_CLASS}]!!\n"); 
        }

    }


    /**
    * Metodo ser carregado dinamicamente
    *
    * @param  unknown_type $_CLASS
    * @param  unknown_type $_PARAM
    * @return object
    */

    public function __call($_CLASS, $_PARAM)
    {

        
        // 
        // 
        // 

        if(isset(self::$_REGIS[ $_CLASS ]) && self::$_REGIS[ $_CLASS ] ) {

            // 
            // 
            // 

            return(self::$_REGIS[ $_CLASS ]);

        } else {

            // 
            // 
            // 

            $INSTANCE_LIMPO = rtrim(self::$PATH_INSTANCE, "/");


            // 
            // 
            // 

            if(self::$CLASS_INSTANCE) {
                
                $CLASS_NEW = str_replace(array("/", "\\"), array(), self::$CLASS_INSTANCE)."\\".$_CLASS; 

                if(self::NameSpaceApi) {

                    // 
                    // 
                    // 

                    $CLASS_NEW = self::NameSpaceApi.$CLASS_NEW;   
                }
            }
            else {

                $CLASS_NEW = $_CLASS; 

                if(self::NameSpaceApi) {
                    
                    // 
                    // 
                    // 

                    $CLASS_NEW = self::NameSpaceApi.$CLASS_NEW;   
                }
            }            

            // 
            // 
            //

            self::$_REGIS[ $_CLASS ] = new $CLASS_NEW( isset($_PARAM[0]) ? $_PARAM[0] : "" ) ;


            // 
            // 
            // 
            
            return(self::$_REGIS[ $_CLASS ]);
        }
    }
}
