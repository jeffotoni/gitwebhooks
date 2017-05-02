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
* @since    Version 0.1
*/

// 
// 
// 

namespace web\src\Hooks;


//
//
//

class WScript
{
    

    private static $msgconcat = "";
    

    // 
    // 
    // 

    private static $show_msg_load = "";

    // 
    // 
    // 

    private static $msg;

    // 
    // 
    // 

    private static $TemplateContent ;

    // 
    // 
    // 

    private static $pathTemplate; 

    // 
    // 
    // 

    private static $pathScript; 


    //
    //
    //

    function __construct()
    {
        # code
    }

    //
    //
    //

    private static function GetTemplate()
    {
        // 
        // use `self` to access class constants from inside the class definition. 
        // 

        return TEMPLATE_DEPLOY;
    } 

    // 
    // 
    // 

    public function LoadTemplate($_ARRAY, $modelo = "beta") 
    {


        // 
        // 
        // 

        $path = ROOT_DIR;

        // 
        // 
        // 

        $modeloName = isset(self::GetTemplate()[$modelo]) ? self::GetTemplate()[$modelo] : "beta";

        // 
        // 
        // 

        $file_template = "{$path}" . PATH_TEMPLATE . "{$modeloName}.sh.php";


        // 
        // 
        // 

        if (is_file($file_template)) {

            //
            //
            //

            self::$msgconcat .= "{$modelo} {$modeloName}";

            // 
            // 
            // 

            $NOME_SCRIPT = $_ARRAY["REPOSITORY"] . "-".$modelo;


            //
            //
            //

            self::$msgconcat .= " {$_ARRAY["REPOSITORY"]}";
            

            // 
            // 
            // 

            self::$pathScript = PATH_SCRIPT . $NOME_SCRIPT . ".sh";

            // 
            // 
            // 

            self::$pathTemplate = $file_template;

            // 
            // 
            // 

            self::$msgconcat .= " File empty create: [{$file_template}]!";

            $content = file_get_contents($file_template);


            // 
            // 
            // 

            if (is_array($_ARRAY)) {

                // 
                // 
                // 

                foreach ($_ARRAY as $key => $value) {
                     
                    // 
                    // 
                    // 

                    $content = str_replace('{'.strtoupper($key).'}', $value, $content);
                }

                // 
                // 
                // 

                self::$TemplateContent = $content;

                // 
                // 
                // 
                // 
            } else {

                exit("erro, not found array LoadTemplate..");
            }

            return $this;

        } else {

            exit("erro, not found file [{$file_template}]..");
        }
    }

    // 
    // 
    // 

    public function LoadFileScript($show=false) 
    {

        
        // 
        // 
        // 

        if(self::$TemplateContent && is_file(self::$pathTemplate)) {

            // 
            // 
            // 

            if($show) {

                print_r(self::$TemplateContent); 
            }

            // 
            // 
            // 

            $PATH_SCRIPT = ROOT_DIR . self::$pathScript;

            //
            //
            //

            self::$pathScript = $PATH_SCRIPT;

            //
            //
            //

            self::$msgconcat .= " {".self::$pathScript."}";

        } else {

            exit("erro, not found file [{".self::$pathTemplate."}]..");
        }

        return $this;
    }

    // 
    // 
    // 

    public function Save()
    {

        // 
        // 
        // 

        if(file_put_contents(self::$pathScript, self::$TemplateContent)) {

            // 
            // 
            // 

            self::$msg = "Saved successfully [{".self::$pathScript."}]!";

            //
            //
            //

            self::$msgconcat .= " {".self::$msg."}";

        } else {

            // 
            // 
            // 

            self::$msg = "Error while saving!";

            self::$msgconcat .= " {".self::$msg."}";
        }

        return $this;
    }

    // 
    // 
    // 

    public function Execute($exec=true)
    {


        if(is_file(self::$pathScript)) {

            //
            //
            //

            if($exec) {

                // 
                // 
                // 

                $COMANDO = "/bin/sh ".self::$pathScript." 2>&1";

                // 
                // Executes even the generated template
                // 

                $LAST_LINE = shell_exec($COMANDO);


                //
                // 
                //

                self::$msgconcat .= " {".$LAST_LINE."}";

                // 
                // 
                // 

                print "\n{$LAST_LINE}\n";
            }

            
        } else {

            exit("erro, not found file [".self::$pathScript."]..");
        }

        return $this;
    }

    //
    //
    //

    public function DelFile()
    {

        //
        //
        //

        if(self::$pathScript && is_file(self::$pathScript)) {

            if(@unlink(self::$pathScript)) {

                $filescriptTmp = explode("/", self::$pathScript);
                $filescript = end($filescriptTmp);

                //
                //
                //

                self::$msgconcat .= " File removed [{$filescript}]!";

            } else {


                $filescriptTmp = explode("/", self::$pathScript);
                $filescript = end($filescriptTmp);

                //
                //
                //

                self::$msgconcat .= " Error while trying to remove file:[{$filescript}]";

            }
        }

        return $this;
    }

    // 
    // 
    // 

    public function AddRepository($gitUser, $repository, $branch, $exec = true)
    {

        //
        //
        //

        if(empty($repository) || empty($branch) || empty($gitUser)) {

            //
            //
            //

            $msg = '{"msg":"Repository, branch and gitUser Are mandatory, can not be empty"}';
            die($msg);

        } else if($branch == "master") {

            //
            //
            //
            
            $msg = '{"msg":"The branch can not be the master"}';
            die($msg);

        } else {

            //
            //
            //

            $path_projects = isset(ARRAY_PROJECT_GIT["gitwebhooks"]) ? ARRAY_PROJECT_GIT["gitwebhooks"] : "";

            //
            //
            //

            if(!$path_projects) {

                $msg = '{"msg":"Directory not found, check the git.repositories file!!!"}';
                die($msg);
            }

            //
            //
            //

            $content_config = $repository. ' = '.$path_projects.'';


            if(file_put_contents(PATH_REPOSITORY , PHP_EOL . PHP_EOL. $content_config, FILE_APPEND)) {

                self::$msgconcat .= " Successfully created content in config git.repositories [$repository]";

                // 
                // load file again
                // 

                $ARRAY_PROJECT_GIT = parse_ini_file(PATH_REPOSITORY); // replace again
             
                //
                //
                //

                define("ARRAY_PROJECT_GIT", $ARRAY_PROJECT_GIT);

            } else {

                //
                //
                //

                $msg = '{"msg":"Error while creating directory ['.$path_projects.']"}';
                die($msg);
            }

            // 
            // create line in file conf
            // 

            $path_repository = "{$path_projects}/{$branch}/{$repository}";
        }


        $_ARRAY["REPOSITORY"]   = $repository;

        $_ARRAY["BRANCH"]       = $branch;
        
        $_ARRAY["GITUSER"]       = $gitUser;

        $_ARRAY["PATH"]         = isset(ARRAY_PROJECT_GIT[$repository]) ? ARRAY_PROJECT_GIT[$repository] : "Erro, I did not find the repository in the git.repositories.conf.php file";

        //
        //
        //

        $lastpos = strlen($_ARRAY["PATH"]) - 1;
        
        if($_ARRAY["PATH"]{$lastpos} != "/") {

            $_ARRAY["PATH"] .= "/";
        }

        //
        //
        //

        $is_repository_exist = $_ARRAY["PATH"] . "{$branch}/{$repository}";

        if(is_dir($is_repository_exist)) {

            die('{"msg":"Repository '.$is_repository_exist.' already exists!"}');
        }

        //
        //
        //
        $modelo = "repository";

        $this->LoadTemplate($_ARRAY, $modelo)
         
                ->LoadFileScript() 

                ->Save()

                ->Execute()

                ->LoadLog();

        die('{"msg":"Repository successfully created [' . $branch . "/" . $repository . ']"}');

    }


    // 
    // 
    // 

    public function LoadLog()
    {

        //
        //
        //

        self::$show_msg_load = date("Y-m-d [H:i]") . " - " . self::$msgconcat . PHP_EOL;

        //
        //
        //

        if(!file_put_contents(PATH_LOG, self::$show_msg_load, FILE_APPEND)) {

            
            die('{"msg":"Error writing log [' . PATH_LOG . ']"}');

        }

        //
        //
        //

        return $this;
    }

    // 
    // 
    // 

    public function Show()
    {

        //
        //
        //

        if(self::$show_msg_load) {

            //
            //
            //

            print "\n";
            print self::$show_msg_load;
            print "\n";
            
        }
        
    }
}
