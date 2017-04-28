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

require_once "config/setenv.conf.php";


// 
// 
// 

if(isset($_POST['payload'], $_GET['key']) && $_GET['key'] == KEY) {


    // 
    // 
    // 

    $vetorJson        = $_POST['payload'];

    // 
    // 
    // 

    $XGitHubEvent     = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : "";

    // 
    // You can prevent by agent the shipments
    // 

    $UserAgent         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

    // 
    // 
    // 

    $HTTP_X_HUB_SIGNATURE = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : "";


    // 
    // 
    // 

    $json = json_decode($vetorJson, true);


    //
    //
    //

    if(!isset($json["ref"])) {

        exit("Error ref payload empty!!!")
    }

    //
    //
    //

    else if(!isset($json["repository"]["id"])) {

        exit("Error ref repository[id] empty!!!")
    }

    //
    //
    //

    else if(!isset($json["repository"]["name"])) {

        exit("Error ref repository[name] empty!!!")
    }


    //
    //
    //

    $ref           = (string) $json["ref"];

    //
    //
    //

    $rep_id        = (string) $json["repository"]["id"];

    //
    //
    //

    $rep_name       = (string) $json["repository"]["name"];

    // 
    // 
    // 

    $tmp = explode("/", $ref);
    $ref_branch = end($tmp);

    // 
    // 
    // 

    $REPOSITORY     = $rep_name;

    // 
    // 
    // 

    $BRANCH     = $ref_branch;

    //
    //
    //
    
    if($REPOSITORY && $BRANCH) {

        // 
        // Loading and running updates
        // 

        $api->WScript()->LoadTemplate(
            [
            "REPOSITORY" => $REPOSITORY,
            "PATH"       => ARRAY_PROJECT_GIT[$REPOSITORY],
            "BRANCH"     => $BRANCH,
            ]
        , $BRANCH)

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
        	
            ->Execute()

            // 
        	// Possibility to delete file, it is not mandatory
        	//

            //->DelFile()

            // 
        	// Generates log on disk so you can keep track of all executions
        	//

            ->LoadLog();
    }
}
