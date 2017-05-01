<?php
/**
*
* @about project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor    @jeffotoni
* @date     25/04/2017
* @since  Version 0.1
*/

// 
// php -S localhost:9001 -t index.php
// 
// OR
// 
// php -S localhost:9001 -t test_index.php 
// 
// Apache .htaccess
// 
// OR
// 
// Ngnix
// 

require_once "../config/setenv.conf.php";

/** 
 * Various ways of instantiating objects
 *
 * Way one:
 * 
 * use web\src\Http\NewRouter;
 * $NewRouter = new NewRouter();
 *
 * Way two:
 *
 * $NewRouter = $api->NewRouter();
 */

//
// Class responsible for handling the responses
//

use web\src\Http\Response as Response;


//
// Class responsible for handling request 
//

use web\src\Http\Request as Request;

//
// Instantiating routes
//

$api->NewRouter()

    //
    //
    //

    ->Methods("POST")

    //
    //
    //

    ->HandleFunc(
        '/github/webhooks', function (Response $response, Request $request) use ($api) {

            //
            // Class responsible for handling requests coming from github
            //

            $GitHub = $api->GitHub();

            $api->GitHub()

                //
                //
                // Authentication coming from github secret
                // For security reasons we suggest you use this option
                // 
                //

                ->AuthenticateSecretToken()


                //
                // Authentication of your key, coming from the URL, a GET
                //

                //->AuthenticateSecretKey()

                //
                // Authentication using md5, header
                //

                //->AuthenticateMd5()

                //
                // Setting the event
                //

                ->Event("push")

                //
                // Making the call to run the deploy scripts
                //

                ->WScript($api)

                // 
                // Loading and running updates
                // 
        
                ->LoadTemplate(
                    [

                    "REPOSITORY" => $GitHub::$REPOSITORY,

                    "PATH"       => ARRAY_PROJECT_GIT[$GitHub::$REPOSITORY],

                    "BRANCH"     => $GitHub::$BRANCH,

                    ]
                )

                // 
                // Generate script from template
                //
          
                ->LoadFileScript()

                // 
                // Prepares to run, saves script to disk
                //
                ->Save()

                // 
                // It runs the script
                //
                //
                ->Execute()

                // 
                // Possibility to delete file, it is not mandatory
                //

                ->DelFile()

                // 
                // Generates log on disk so you can keep track of all executions
                //
                //
                ->LoadLog();

        }
    )

    //
    // It will execute the methods
    //

    ->Run();


//
// 
//

$api->NewRouter()

    //
    //
    //

    ->Methods("GET")

    //
    //
    //

    ->HandleFunc(
        '/webhooks/status', function (Response $response, Request $request) use ($api) {

            //
            //
            //

            $api->GitWebHooks()

            //
            //
            //

                ->AuthenticateMd5();

            //
            //
            //

            $arrayJson = [
            
            "status" => "online",
            ];

            $response->WriteJson($arrayJson);

        }
    )->Run();
