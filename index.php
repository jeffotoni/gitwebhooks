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

#
#
require_once "config/setconfig.php";

echo "\nStart!!\n";

#
#
#
if(isset($_POST['payload'], $_GET['idkeys3']) && $_GET['idkeys3'] == '123456789') {


	#
	#
	#
	$vetorJson 		= $_POST['payload'];

	#
	#
	#
	$XGitHubEvent 	= $_SERVER['HTTP_X_GITHUB_EVENT'];

	#
	#
	#
	$UserAgent 		= $_SERVER['HTTP_USER_AGENT'];

	#
	#
	#
	$HTTP_X_HUB_SIGNATURE = $_SERVER['HTTP_X_HUB_SIGNATURE'];


	#
	#
	#
	$json = json_decode($vetorJson, true);

	$ref 		= $json["ref"];
	$rep_id 	= $json["repository"]["id"];
	$rep_name 	= $json["repository"]["name"];

	#
	#
	#
	$tmp = explode("/", $ref);
	$ref_branch = end($tmp);

	#
	#
	#
	$REPOSITORY 	= $rep_name;

	#
	#
	#
	$BRANCH 	= $ref_branch;

	if($REPOSITORY && $BRANCH){

		#
		#
		#
		$api()->WebHooks()->LoadTemplate([

				"REPOSITORY" => $REPOSITORY,
				"BRANCH" => $BRANCH,
			])

		->LoadFileScript()
			->Save()
				->Execute()
					->LoadLog();

	}	
}
