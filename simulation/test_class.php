<?php
/**
*
* @about 	project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor 	 jeffotoni
* @date 	 25/04/2017
* @copyright Copyright (c) 2017-2017 Jefferson Otoni
* @since     Version 0.1
* @link      https://github.com/jeffotoni/gitwebhook
*/


/** 
 *
 * Config of the system is where the page is created
 *  
 */

require_once "../config/setenv.conf.php";


/** 
 *
 * Testing possibilities of instantiating classes, using 
 * objects already predefined by the mini framework
 *
 * 
 */

/** 
 *
 * Using routes
 *
 * We have several ways to use a class
 * 
 * one:
 * 
 * use web\src\Http\Router;
 * $router = new Router();
 *
 * Two:
 * 
 * $router = new web\src\Http\Router();
 *
 * three:
 *
 * $router = $api->Router();
 * 
 * 
 */



/** One way to instantiate your classes is by using new, normally. */

$ShowUp = new web\src\Message\Logs\ShowUp();

$ShowUp->ShowError();

// OR

use web\src\Message\Logs\ShowUp as Shup;

$ShowUp = new Shup();

////////////////////////////////////////////

$ShowUp->ShowError();

$HandlerMsg = new web\src\Message\HandlerMsg();

$HandlerMsg->createEnvironment();


/** 
 * 
 * Another way to instantiate is to use $ api object, made available by the
 * framework, it can access all its classes dynamically without needing new. 
 * 
 */

// 
// Hooks()->GitHub()->TestClass() 
// 

$api->GitHub()->TestClass();

//
// Message->Logs()->RequestResponse()->GetRequest()
//

$api->RequestResponse()->GetRequest();

//
// The framework does singleton, if you have already made the 
// call when doing it again it will be returned what is in the memory
//

$api->Show($api->GitHub()->TestClass());

$api->Request()->getAttribute();

$api->HandlerMsg()->createEnvironment();

$api->ShowUp()->ShowError();

$api->Show($api->RequestResponse()->GetRequest());


/** 
 * 
 * Testing interfaces now
 * 
 */

//$api->HttpTemplate()->showMsg();

// or 

//$api->TemplateHttp()->HttpTemplate()->showMsg();

/** This will display an error 
*
################################################
Error, class does not exist [HttpTemplate]!!
################################################
*/

// Will not work
// 
//

//$templateHttp = new web\src\Http\HttpTemplate();

