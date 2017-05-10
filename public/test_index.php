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
//
//

use web\src\Http\Response as Response;

//
//
//
use web\src\Http\Request as Request;

// //
// // 
// //

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

                // ->AuthenticateSecretToken()


                //
                // Authentication of your key, coming from the URL, a GET
                //

                //->AuthenticateSecretKey()
            
                //
                // Authentication using md5, header
                //

                ->AuthenticateMd5()

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

                    ],  "simulation"
                )

                // 
                // Generate script from template
                //
          
                ->LoadFileScript(true)

                // 
                // Prepares to run, saves script to disk
                //
                ->Save()

                // 
                // It runs the script
                //
                //
                ->Execute(true)

                // 
                // Possibility to delete file, it is not mandatory
                //

                //->DelFile()

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
        '/webhooks/repository/add/{name}', function (Response $response, Request $request) use ($api) {


            //
            // Authentication
            // 

            $api->GitWebHooks()

                //
                //
                //
                ->AuthenticateMd5();

            //
            //
            //
            
            $branch     = $request->GetBranch();
            
            $repository = $request->GetName();

            $gitUser    = $request->GitUser();

            //
            //
            //
            
            $api->WScript()->AddRepository($gitUser, $repository, $branch);

        }
        
    )->Run();


//
// under development template
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
        '/webhooks/template/add/{name}', function (Response $response, Request $request) use ($api) {


            //
            // Authentication
            // 

            $api->GitWebHooks()

                //
                //
                //
                ->AuthenticateMd5();

            //
            //
            //
            
            //$branch     = $request->GetBranch();
            
            $template = $request->GetName();

            $gitUser    = $request->GitUser();

            //
            //
            //
            echo "\nAdd Template: {$template}";
            //$api->WScript()->AddTemplate($gitUser, $branch);

        }
        
    )->Run();

//
// under development branch
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
        '/webhooks/branch/add/{name}', function (Response $response, Request $request) use ($api) {


            //
            // Authentication
            // 

            $api->GitWebHooks()

                //
                //
                //
                ->AuthenticateMd5();

            //
            //
            //
            
            //$branch     = $request->GetBranch();
            
            $branch = $request->GetName();

            $gitUser    = $request->GitUser();

            //
            //
            //
            
            $api->WScript()->AddBranch($gitUser, $branch);

        }
        
    )->Run();