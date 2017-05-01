<?php
/**
*
* @about    project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor    @jeffotoni
* @date     25/04/2017
* @since    Version 0.1
*/

final class ClassAutoLoading
{

    //
    //
    //
    
    public function __construct() 
    {

        //
        //
        //

        spl_autoload_register(array($this, 'autoloader'));

    }

    //
    //
    //

    private function autoloader($className) 
    {

        //echo "\n",'Trying to load ', $className, ' via ', __METHOD__, "()\n\n";

        //
        //
        //
        
        $pathClass = str_replace(array("\\"), array("/"), $className);

        //
        //
        //

        $PATH_CLASS = $pathClass . ".php";

        //
        //
        //

        if(is_file($PATH_CLASS)) {

            //
            //
            //

            include  $pathClass . ".php";

        } else {

            print("################################################\n");
            print("Error, class does not exist [{$pathClass}]!!\n");
            print("################################################\n\n");
            
            //return $this;  
            //exit(0);
        }
    }
}
