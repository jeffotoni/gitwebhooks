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
$PATH_CLEN = str_replace(array("public/",
                                "simulation/",
                                "config/", 
                                "templates/"), array(), getcwd() . "/");

//
//
//

define("PATHSET_LOCAL", $PATH_CLEN);

//
//
//
chdir(PATHSET_LOCAL);

//
//
//

$PATH_SETCONF = PATHSET_LOCAL . "config/setconfig.conf.php" ;

if(!is_file($PATH_SETCONF)) {

$SETCONF_PHP = '<?php
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

define("ROOT_DIR", getcwd() . "/");

//
//
//

define("GITHUB_SECRET", "12345");


//
//
//

define("KEY", "b118eda467d926d003f9b4af9c203994");

//
//
//

define("GITWEBHOOKS_SECRET", "827ccb0eea8a706c4c34a16891f84e7b");

// 
// 
// 

define("PATH_LOG", ROOT_DIR. "log/github-webooks.log");

// 
// 
// 

define("PATH_TEMPLATE", "templates/");

// 
// 
// 

define("PATH_SCRIPT", "scripts/");


// 
// 
// 

define("PATH_CLASS", "web/src");


// 
// 
// 

define("PATH_CLASS_NAMESPACE", "web\src");

//
//
//

define("TEMPLATE_DEPLOY", [

    "beta"        => "template-script-deploy",
    "test"        => "template-script-deploy",
    "product"     => "template-script-deploy",
    "simulation"  => "simulation-example",

    ]
   );

// 
// 
// 

define("ARRAY_PROJECT_GIT", [
    
    /** 
     *
     * or /var/www/gitmyprojects/
     *
     * example:
     * 
     * /var/www/gitmyprojects/beta
     * /var/www/gitmyprojects/product
     * /var/www/gitmyprojects/test
     *
     * /var/www/gitmyprojects/beta/project1.git
     * /var/www/gitmyprojects/beta/project2.git
     * 
     * /var/www/gitmyprojects/product/project1.git
     * /var/www/gitmyprojects/product/project2.git
     *
     * Configuring your paths
     * 
     * gitwebhooks  => /var/www/gitmyprojects
     *
     * yourprojetc1 => /var/www/gitmyprojects
     *
     * yourproject2 => /var/www/gitmyprojects
     *
     *  OR
     *  
     * gitwebhooks  => ../../../../../
     *
     * yourprojetc1 => ../../../../../
     *
     * yourproject2 => ../../../../../
     * 
     */
    
    "gitwebhooks"      => "../../../",

    "yourprojetc1"     => "../../../../",

    "yourproject2"    => "../../../../",

    "yourproject3"    => "../../../../",

    "yourproject4"    => "../../../../",

    "yourproject5"    => "../../../../",

    "yourproject6"    => "../../../../",

    "yourproject7"    => "../../../../",

    ]
);


// 
// 
// 

$apifunc2 = function ($namespace) {
    
    //
    //
    //

    $path_n = str_replace(array("\\\"), array("/"), $namespace);

    //
    //
    //
    
    $path_n = ROOT_DIR.$path_n . ".php";


    if(is_file($path_n)) {

        //
        //
        //

        include_once $path_n;

        // 
        // 
        // 

        $classApi = new $namespace;

        //
        //
        //

        return $classApi;

    } else {

        exit("\nFile not exist! [{$path_n}]");

    }
};

//
//
//

$api = $apifunc2(PATH_CLASS_NAMESPACE. "\AutoLoading");



// 
// 
// 

$loader = function ($namespace, $class="") {
    
    //
    //
    //

    $path_n = str_replace(array("\\\"), array("/"), $namespace);

    //
    //
    //
    
    $path_n = ROOT_DIR.$path_n . ".php";


    if(is_file($path_n)) {

        //
        //
        //

        include_once $path_n;

        // 
        // 
        // 
        if($class) {


            $classApi = new $class;

        } else {

            $classApi = new $namespace;
        }

        //
        //
        //

        return $classApi;

    } else {

        exit("\nFile not exist! [{$path_n}]");

    }
};


//
//
//

$LoaderAuto = $loader(PATH_CLASS . "/ClassAutoLoading", "ClassAutoLoading");

';
	
	//
	//
	//

	file_put_contents($PATH_SETCONF, $SETCONF_PHP . PHP_EOL);

	require_once $PATH_SETCONF;

} else {


	//
	//
	//

	require_once $PATH_SETCONF;
}