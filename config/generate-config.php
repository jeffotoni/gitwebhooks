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
 * Generate config, after its generation editao file setconf.conf.php
 */

$api->HttpConfig()->Generate();
