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
// 

namespace web\src;

//use App\Core as Core; 

//
//
//

final class AutoLoading
{

    // 
    // 
    // 

    private static $PATH_APIS = "web/src";

    //
    //
    //

    const NameSpaceCollect = [

    "Api" => "web\\src",

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
    
    private static $collectionClass = array();

    // 
    // 
    //

    public function __construct($instance=null)
    {
        

        // 
        // 
        // 

        if(!defined("PATH_ROOT_API")) {

            define("PATH_ROOT_API", getcwd() . "/" . trim(str_replace("\\", "/", self::NameSpaceCollect["Api"]), "/")); 
        }

        // 
        // 
        // 

        self::SAutoLoaderPath();

    }

   

    // 
    // 
    // 

    private static function SAutoLoaderPath()
    {

        
        //
        //
        //

        self::$PATH_INSTANCE = PATH_ROOT_API;

    }

    //
    //
    //

    private static $CALL_CLASS = null;
    
    //
    //
    //

    private static $CALL_CLASS_NAMESPACE = null;

    //
    //
    //

    private static $CALL_CLASS_PATH = null;


    //
    //
    //

    private static $CALL_CLASS_INSTANCE = null;

    /**
    * Metodo ser carregado dinamicamente
    *
    * @param unknown_type $_CLASS
    */

    private function autoLoadingLoader($_CLASS)
    {


        // 
        // 
        // 

        $_CLASS_CLEAN_NAME = explode("\\", $_CLASS); 

        // 
        // 
        // 

        $_CLASS_CLEAN_NAME = trim(end($_CLASS_CLEAN_NAME), "/");

        //
        //
        //

        $FOLDER_CLASS = self::FindDirClass($_CLASS_CLEAN_NAME);


        //
        //
        //

        $NEW_PATH_CLASS = ROOT_DIR . $FOLDER_CLASS . "/" . $_CLASS_CLEAN_NAME . ".php"; 


        //
        //
        //

        if(is_file($NEW_PATH_CLASS)) {
                
            //
            // 
            //  

            self::$CALL_CLASS = $_CLASS_CLEAN_NAME;

            //
            //
            //

            self::$CALL_CLASS_PATH = $NEW_PATH_CLASS;

            //
            //
            //

            self::$CALL_CLASS_NAMESPACE = ltrim(str_replace(array("/"), array("\\"), $FOLDER_CLASS), "\\");

            //
            //
            //

            self::$CALL_CLASS_INSTANCE = self::$CALL_CLASS_NAMESPACE . "\\" . self::$CALL_CLASS;

            //
            //
            //

            include_once $NEW_PATH_CLASS;

            // echo "\nautoload \n";

            // set_include_path(self::$CALL_CLASS_PATH);

            // spl_autoload_extensions('.php');

            // spl_autoload("GitHub");



        } else { 
            
            //exit(trigger_error("\n\nnot file in path [{$NEW_PATH_CLASS}]!!\n"));
            return $this;
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
        // singleton
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

            spl_autoload_register(array( $this, 'autoLoadingLoader' ));

            //
            //
            //
            $this->autoLoadingLoader($_CLASS);
        }

        //
        //
        //

        $CALL_CLASS_INSTANCE  = self::$CALL_CLASS_INSTANCE;

        
        //if(inte)
        
        //
        // Class collection
        //

        self::$_REGIS[$_CLASS] = new $CALL_CLASS_INSTANCE(isset($_PARAM[0]) ? $_PARAM[0] : "") ;

        //
        //
        //

        self::Destroy();

        // 
        // 
        // 
        
        return(self::$_REGIS[ $_CLASS ]);
    }

    
    /**
 * [Destroy description] 
*/

    private static function Destroy() 
    {


        //
        // 
        //  

        self::$CALL_CLASS = null;

        //
        //
        //

        self::$CALL_CLASS_PATH = null;

        //
        //
        //

        self::$CALL_CLASS_NAMESPACE = null;

        //
        //
        //

        self::$CALL_CLASS_INSTANCE = null;
    }
    
    
    /**
 * [FindDirClass description] 
*/

    private static function FindDirClass($className, $dirclass = "") 
    {

        if(empty($dirclass)) {
            $dirclass = self::$PATH_APIS; 
        }
        
        //
        //
        //
        $path_class = ROOT_DIR.$dirclass;

        if(is_dir($path_class)) {

            //echo "\n Exist!\n";
            $classDir = dir($path_class);

        } else {

            //echo "\nNot Exist! \n{$path_class}\n";
            exit(trigger_error("\n\nnot exist path [{$path_class}]!!\n"));
        }

        //
        //
        //

        self::$collectionClass[$dirclass] = $className;

        //
        // Returns the first one you find
        //

        if(file_exists(ROOT_DIR.$dirclass.'/'.$className.'.php')) { return $dirclass; 
        }

        else {

            //
            //
            //

            while (false!==($entry=$classDir->read())) {

                //
                //
                //

                $checkclass=$dirclass.'/'.$entry;

                //
                //
                //

                if(strlen($entry)>2) {

                    //
                    //
                    //

                    if(is_dir(ROOT_DIR.$checkclass)) {

                        //
                        //
                        //

                        self::$collectionClass[$checkclass] = $className;

                        if(file_exists(ROOT_DIR.$checkclass.'/'.$className.'.php')) { return $checkclass; 
                        }

                        else {

                            //
                            //
                            //

                            $subdirclass=self::FindDirClass($className, $checkclass);

                            //
                            //
                            //

                            if($subdirclass) { return $subdirclass; 
                            }
                        }
                    }
                }
            } 
        }

        //
        //
        //
        $classDir->close();

        return 0;
    }

    /**
 * [ShowClassColection description] 
*/

    public function ShowClassColection() 
    {

        

        print "\n";
        print_r(self::$collectionClass);
        print "\n\n";

    }


    /**
 * [Show description] 
*/

    public function Show($string) 
    {

        print "\n";
        echo $string;
        print "\n\n";
    }
}
