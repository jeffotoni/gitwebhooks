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
#
namespace web\src\Hooks;


/**
* 
* WScript 
*
* 
*/
class WScript
{
	
	const TEMPLATE_DEPLOY = [

		"beta"		=> "template-script-deploy",
		"test" 		=> "template-script-deploy",
		"product" 	=> "template-script-deploy",
	];

	private static $msgconcat = "";
	private static $msg;

	#
	#
	#
	private static $TemplateContent ;

	#
	#
	#
	private static $pathTemplate; 

	#
	#
	#
	private static $pathScript; 


	function __construct()
	{
		# code...
	}

	private static function GetTemplate()
    {
        /* use `self` to access class constants from inside the class definition. */
        return self::TEMPLATE_DEPLOY;
    } 

	#
	#
	#
	public function LoadTemplate($_ARRAY, $modelo = "beta") {

		#
		#
		#
		$path = PATH_FISICO;

		#
		#
		#
		$modeloName = isset(self::GetTemplate()[$modelo]) ? self::GetTemplate()[$modelo] : "beta";

		#
		#
		#
		$file_template = "{$path}/" . PATH_TEMPLATE . "{$modeloName}.sh.php";

		#
		#
		#
        if (is_file($file_template)) 
        {

        	self::$msgconcat .= "{$modelo} {$modeloName}";
        	#
        	#
        	#
        	$NOME_SCRIPT = $_ARRAY["REPOSITORY"] . "-".$modelo;


        	self::$msgconcat .= " {$_ARRAY["REPOSITORY"]}";
        	#
        	#
        	#
        	self::$pathScript = PATH_SCRIPT . $NOME_SCRIPT . ".sh";

        	#
        	#
        	#
        	self::$pathTemplate = $file_template;

        	#
        	#
        	#
            $content = file_get_contents($file_template);


            #
            #
            #
            if (is_array($_ARRAY)) {

            	#
            	#
            	#
                foreach ($_ARRAY as $key => $value) {
                 	
                 	#
                 	#   
                 	#         
                    $content = str_replace('{'.strtoupper($key).'}', $value, $content) ;
                }

                #
                #
                #
                self::$TemplateContent = $content;

                #
                #
                #
            } else {exit("erro, not found array LoadTemplate..");}

            return $this;

        } else {exit("erro, not found file [{$file_template}]..");}
	}

	#
	#
	#
	public function LoadFileScript($show=false) {

		
		#
		#
		#
		if(self::$TemplateContent && is_file(self::$pathTemplate)) {

			#
			#
			#
			if($show)
				print_r(self::$TemplateContent);

			#
			#
			#
			$PATH_SCRIPT = PATH_FISICO . self::$pathScript;

			self::$pathScript = $PATH_SCRIPT;

			self::$msgconcat .= " {".self::$pathScript."}";

		} else {

			exit("erro, not found file [{".self::$pathTemplate."}]..");
		}

		return $this;
	}

	#
	#
	#
	public function Save(){

		#
		#
		#
		if(file_put_contents(self::$pathScript, self::$TemplateContent)){

			#
			#
			#
			self::$msg = "Saved successfully!";

			self::$msgconcat .= " {".self::$msg."}";

		} else {

			#
			#
			#
			self::$msg = "Error while saving!";

			self::$msgconcat .= " {".self::$msg."}";
		}

		return $this;
	}

	#
	#
	#
	public function Execute(){


		if(is_file(self::$pathScript)){

			#
			#
			# 
			$COMANDO = "/bin/sh ".self::$pathScript." 2>&1";

			#
			#
			#
			$LAST_LINE = shell_exec($COMANDO);

			self::$msgconcat .= " {".$LAST_LINE."}";

			#
			#
			#
			print "\n{$LAST_LINE}\n";

		} else {

			exit("erro, not found file [".self::$pathScript."]..");
		}

		return $this;
	}

	#
	#
	#
	public function LoadLog(){


		$string_log = date("Y-m-d [H:i]") . " - " . self::$msgconcat . PHP_EOL;

		file_put_contents(PATH_LOG, $string_log , FILE_APPEND);
	}

	#
	#
	#
	public function Show(){

		print "\n";
		print self::$msg;
		print "\n";
	}
}
