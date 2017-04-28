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

  
  // test curl conexao
  // 
  //   

  echo "\nShow Methods\n\n";

  //
  //
  //
  
  print_r($_GET);

  //
  //
  //
 
  print_r($_POST);

<<<<<<< HEAD
  //
  // github sen POST width payload
  //

=======

  $api->GitHubCheck()->CheckPost();
  
>>>>>>> beta
  if(isset($_POST['payload'], $_GET['key']) && $_GET['key'] == KEY) {

    //
    //
    //
    //

    $json = $vetorJson        = isset($_POST['payload']) ? $_POST['payload'] : "";

    //
    // decode string json to array
    //

    $json = json_decode($vetorJson, true);

    //
    // refs/heads/beta or refs/heads/master or refs/heads/product etc..
    //

    $ref           = (string) $json["ref"];

    if($ref) {

      print "\n======= json ref sucess !!! ========";
      echo "\n";
      echo $ref;
      echo "\n";  

    }
  }
  
  // 
  // just github
  // 

  $XGitHubEvent     = isset($_SERVER['HTTP_X_GITHUB_EVENT']) ? $_SERVER['HTTP_X_GITHUB_EVENT'] : "";

  // 
  // just github
  // 

  $UserAgent         = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

  // 
  // just github
  // 

  $HTTP_X_HUB_SIGNATURE = isset($_SERVER['HTTP_X_HUB_SIGNATURE']) ? $_SERVER['HTTP_X_HUB_SIGNATURE'] : "";


// 
// test 
// 

$BRANCH = "beta";

// 
// 
// 

$REPOSITORY = "gitwebhooks";

// 
// 
// 

$api->WScript()->LoadTemplate(
    [

                "REPOSITORY" => $REPOSITORY,
                "PATH"       => ARRAY_PROJECT_GIT[$REPOSITORY],
                "BRANCH"     => $BRANCH,
            ]
,  "simulation" )

   ->LoadFileScript(true)
            ->Save()
            ->Execute(true)
            ->DelFile()
            ->LoadLog();
// 
// 
// 

// $api->WScript()->LoadTemplate(
//     [

//         "REPOSITORY" => "repositorio2",
//         "BRANCH" => "beta",
//     ]
// )
//     ->LoadFileScript();

// // 
// // 
// // 
    
// $api->WScript()->LoadTemplate(
//     [

//         "REPOSITORY" => "repositorio3",
//         "BRANCH" => "beta",
//     ]
// )
//     ->LoadFileScript();
