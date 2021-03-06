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

        return BRANCH_TEMPLATE_DEPLOY;
    } 

    // 
    // 
    // 

    public function LoadTemplate($_ARRAY, $modelo = "beta", $created = false) 
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

        if(isset($_ARRAY['BRANCH'], $_ARRAY['PATH']) && $_ARRAY['BRANCH'] && $_ARRAY['PATH']) {

            if($modelo!="repository" && $created == false )
                self::IsValidRepository($_ARRAY);
            else 
                self::IsValidBranch($_ARRAY);

        } else {

            

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"Repository, branch and PATH are mandatory, can not be empty"}';
            self::$msgconcat .= $msg;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);
        }

        // 
        // 
        // 

        if (is_file($file_template)) {

            //
            //
            //
            self::$msgconcat .= "Template Deploy:" . PHP_EOL;
            self::$msgconcat .= "{$modelo}".PHP_EOL."Name Template: {$modeloName}" . PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;

            // 
            // 
            // 

            $NOME_SCRIPT = $_ARRAY["REPOSITORY"] . "-".$modelo;


            //
            //
            //
            self::$msgconcat .= "Repository:" . PHP_EOL;
            self::$msgconcat .= "{$_ARRAY["REPOSITORY"]}".PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;
            

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
            self::$msgconcat .= "Create:" . PHP_EOL;
            self::$msgconcat .= "File empty create: [{$file_template}]!".PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;

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

                //
                //
                // 
                self::$msgconcat .= "Error: " . PHP_EOL;
                $msg = '{"msg":"erro, not found array LoadTemplate.."}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "" . PHP_EOL;

                //
                //
                //

                $this->LoadLog();

                //
                //
                //

                die($msg);

            }

            return $this;

        } else {

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"erro, not found file [' . $file_template . '].."}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "" . PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);
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

            self::$msgconcat .= "Script Path: " . PHP_EOL;
            self::$msgconcat .= "{".self::$pathScript."}".PHP_EOL;
            self::$msgconcat .= "" . PHP_EOL;

        } else {

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"erro, not found file ['.self::$pathTemplate.']"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "" . PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);

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
            self::$msgconcat .= "Save: " . PHP_EOL;
            self::$msgconcat .= '{"msg":"'.self::$msg.'""}'.PHP_EOL;
            self::$msgconcat .= "".PHP_EOL;

        } else {

            // 
            // 
            // 

            self::$msg = "Error while saving!";

            self::$msgconcat .= "Error Save:" . PHP_EOL;
            self::$msgconcat .= '{"msg":"'.self::$msg.'"}'.PHP_EOL;
            self::$msgconcat .= "".PHP_EOL;
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
                self::$msgconcat .= "SHELL SCRIPT: " . PHP_EOL;
                self::$msgconcat .= "Execute shell script " . PHP_EOL;
                self::$msgconcat .= "".$LAST_LINE."".PHP_EOL;

                // 
                // 
                // 

                print "\n{$LAST_LINE}\n";
            }

            
        } else {


            $msg = '{"msg":"erro, not found file [' . self::$pathScript . ']"}' . PHP_EOL;

            self::$msgconcat .= "Error:" . PHP_EOL;
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            // save log
            //

            $this->LoadLog();

            die($msg);
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
                self::$msgconcat .= "Script:" . PHP_EOL;
                self::$msgconcat .= "Removed [{$filescript}]!" . PHP_EOL;
                self::$msgconcat .= "".PHP_EOL;

            } else {


                $filescriptTmp = explode("/", self::$pathScript);
                $filescript = end($filescriptTmp);

                //
                //
                //
                self::$msgconcat .= "Error:" . PHP_EOL;
                self::$msgconcat .= "Error while trying to remove file:[{$filescript}]" . PHP_EOL;
                self::$msgconcat .= "".PHP_EOL;

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
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"Repository, branch and gitUser Are mandatory, can not be empty"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);


        } else if($branch == "master") {

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"The branch can not be the master"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);

        } else {

            //
            //
            //

            $path_projects = isset(ARRAY_PROJECT_GIT["gitwebhooks"]) ? ARRAY_PROJECT_GIT["gitwebhooks"] : "";

            //
            // Ensuring you have a / at the end
            //

            $path_projects = rtrim($path_projects, "/");
            $path_projects .= "/";

            //
            //
            //

            if(!$path_projects) {

                //
                //
                // 
                self::$msgconcat .= "Error: " . PHP_EOL;
                $msg = '{"msg":"Directory not found, check the git.repositories file!!!"}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "".PHP_EOL;

                //
                //
                //

                $this->LoadLog();

                //
                //
                //
                
                die($msg);
            }

            //
            //
            //

            $path_repository = "{$path_projects}{$branch}/{$repository}";
            

            //
            //
            //

            $path_branch = "{$path_projects}{$branch}";

             if(!is_dir($path_branch)) {

                //
                //
                // 
                self::$msgconcat .= "Error: " . PHP_EOL;
                $msg = '{"msg":"Directory branch not found, ['.$path_branch.'] so we can create new repository !!!"}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "".PHP_EOL;

                //
                //
                //

                $this->LoadLog();

                //
                //
                //
                
                die($msg);
            }
            //
            //
            //

            $content_config = $repository. ' = '.$path_projects.'';

            // 
            // check repository
            // 

            $ARRAY_PROJECT_GIT = parse_ini_file(PATH_REPOSITORY); // replace again

            if(!array_key_exists($repository, $ARRAY_PROJECT_GIT)) {

                // If there is no need to record

                if(file_put_contents(PATH_REPOSITORY , PHP_EOL . PHP_EOL. $content_config, FILE_APPEND)) {

                    self::$msgconcat .= "Successfully: " . PHP_EOL;
                    self::$msgconcat .= "Successfully created content in config git.repositories [$repository]" . PHP_EOL;
                    self::$msgconcat .= "".PHP_EOL;

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
                    self::$msgconcat .= "error " . PHP_EOL;
                    $msg = '{"msg":"Error while creating directory ['.$path_projects.']"}' . PHP_EOL;
                    self::$msgconcat .= $msg;
                    self::$msgconcat .= "".PHP_EOL;

                    $this->LoadLog();

                    die($msg);
                }

            }

            // 
            // create line in file conf
            // 

            $path_repository = "{$path_projects}/{$branch}/{$repository}";
        }


        $_ARRAY["REPOSITORY"]   = $repository;

        $_ARRAY["BRANCH"]       = $branch;
        
        $_ARRAY["GITUSER"]      = $gitUser;

        $_ARRAY["PATH"]         = isset(ARRAY_PROJECT_GIT[$repository]) ? ARRAY_PROJECT_GIT[$repository] : "Erro, I did not find the repository in the git.repositories.conf.php file";

       //
       // Repository can not exist
       //

       self::IsValidBranch($_ARRAY);

       //
       //
       //

       $modelo = "repository";

       $this->LoadTemplate($_ARRAY, $modelo, true)
         
                ->LoadFileScript() 

                ->Save()

                ->Execute()

                ->LoadLog();

        //
        //
        // 
        self::$msgconcat .= "Created:" . PHP_EOL;
        $msg = '{"msg":"Repository successfully created [' . $branch . "/" . $repository . ']"}';
        self::$msgconcat .= $msg;
        self::$msgconcat .= "".PHP_EOL;

        //
        //
        //

        $this->LoadLog();

        //
        //
        //
        
        die($msg);

    }

    // 
    // 
    // 

    public function AddBranch($gitUser, $branch, $exec = true)
    {

        //
        //
        //

        if(empty($branch) || empty($gitUser)) {

            //
            //
            //
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"branch and gitUser Are mandatory, can not be empty"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

            die($msg);


        } else if($branch == "master") {

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"The branch can not be the master"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //

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

                //
                //
                // 
                self::$msgconcat .= "Error: " . PHP_EOL;
                $msg = '{"msg":"Directory not found, check the git.repositories file!!!"}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "".PHP_EOL;

                //
                //
                //

                $this->LoadLog();

                //
                //
                //
                
                die($msg);
            }

            //
            //
            //

            $path_branch = "{$path_projects}/{$branch}";
            

            //
            // repository  = template-script-add-repository
            //

            $content_config = $branch. ' = template-script-deploy';

            // 
            // check repository
            // 

            $ARRAY_BRANCH_DEPLOY = parse_ini_file(PATH_BRANCH_CREATE); // replace again


            if(!array_key_exists($branch, $ARRAY_BRANCH_DEPLOY)) {

                // If there is no need to record

                if(file_put_contents(PATH_BRANCH_CREATE , PHP_EOL . PHP_EOL. $content_config, FILE_APPEND)) {

                    self::$msgconcat .= "Successfully: " . PHP_EOL;
                    self::$msgconcat .= "Successfully created content in config git.branch [$branch]" . PHP_EOL;
                    self::$msgconcat .= "".PHP_EOL;

                    // 
                    // load file again
                    // 

                    $ARRAY_BRANCH = parse_ini_file(PATH_BRANCH_CREATE); // replace again
                 
                    //
                    //
                    //

                    define("BRANCH_TEMPLATE_DEPLOY", $ARRAY_BRANCH);

                } else {

                    //
                    //
                    //

                    self::$msgconcat .= "error " . PHP_EOL;
                    $msg = '{"msg":"Error while creating directory ['.$path_projects.']"}' . PHP_EOL;
                    self::$msgconcat .= $msg;
                    self::$msgconcat .= "".PHP_EOL;

                    $this->LoadLog();

                    die($msg);
                }

            }

            // 
            // create line in file conf
            // 

            $path_branch = "{$path_projects}/{$branch}";
        }

        exit("under development");

        $_ARRAY["REPOSITORY"]   = $repository;

        $_ARRAY["BRANCH"]       = $branch;
        
        $_ARRAY["GITUSER"]      = $gitUser;

        $_ARRAY["PATH"]         = isset(ARRAY_PROJECT_GIT[$repository]) ? ARRAY_PROJECT_GIT[$repository] : "Erro, I did not find the repository in the git.repositories.conf.php file";

       //
       // Repository can not exist
       //

       self::IsValidBranch($_ARRAY);

       //
       //
       //

       $modelo = "repository";

       $this->LoadTemplate($_ARRAY, $modelo, true)
         
                ->LoadFileScript() 

                ->Save()

                ->Execute()

                ->LoadLog();

        //
        //
        // 
        self::$msgconcat .= "Created:" . PHP_EOL;
        $msg = '{"msg":"Repository successfully created [' . $branch . "/" . $repository . ']"}';
        self::$msgconcat .= $msg;
        self::$msgconcat .= "".PHP_EOL;

        //
        //
        //

        $this->LoadLog();

        //
        //
        //
        
        die($msg);

    }

    //
    //
    //

    public function IsValidRepository($_ARRAY) {

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

        $is_repository_exist = $_ARRAY["PATH"] . "{$_ARRAY["BRANCH"]}/{$_ARRAY["REPOSITORY"]}";

        //
        //
        //
        
       if(!is_dir($is_repository_exist)) {

            //
            //
            // 
            self::$msgconcat .= "does not exist repository:" . PHP_EOL;
            $msg = '{"msg":"Repository '.$is_repository_exist.' does not exist, we can not update!"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //
            
            die($msg);
        }
        
        return null;
    }

    //
    //
    //

    public function IsValidBranch($_ARRAY) {

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

        $is_repository_exist = $_ARRAY["PATH"] . "{$_ARRAY["BRANCH"]}/{$_ARRAY["REPOSITORY"]}";

        if(is_dir($is_repository_exist)) {

            //
            //
            //

            self::$msgconcat .= "Trying to add new repository:" . PHP_EOL;
            $msg = '{"msg":"Repository '.$is_repository_exist.' already exists!"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;

            //
            //
            //

            $this->LoadLog();

            //
            //
            //
            
            die($msg);

        } else {

            // 
            // Only the branch exists?
            // 

            $is_branch_exist = $_ARRAY["PATH"] . "{$_ARRAY["BRANCH"]}";

            if(!is_dir($is_branch_exist)) {

                 //
                //
                //

                self::$msgconcat .= "Erro trying to add new branch/repository :" . PHP_EOL;
                $msg = '{"msg":"Branch '.$is_branch_exist.' does not exist, can not create new repository. ['.print_r($_ARRAY,true).']"}';
                self::$msgconcat .= $msg;
                self::$msgconcat .= "".PHP_EOL;

                //
                //
                //

                $this->LoadLog();

                //
                //
                //
                
                die($msg);
            }


        }
        
        return null;
    }

    // 
    // 
    // 

    public function LoadLog()
    {

        //
        //
        //

        $IP = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : "";

        //
        //
        //

        $HTTP_USER_AGENT = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : "";

        //
        //
        //
        $msgtmp = PHP_EOL . "-------------------------------------------------- START WScript -------------------------------------------------- " . PHP_EOL;
        $msgtmp .= date("Y-m-d [H:i]") . " [{$IP}] [{$HTTP_USER_AGENT}]" . PHP_EOL;
        $msgtmp .= "" . PHP_EOL;
        self::$show_msg_load = $msgtmp . self::$msgconcat . PHP_EOL;

        //
        //
        //

        if(!file_put_contents(PATH_LOG, self::$show_msg_load, FILE_APPEND)) {

            //
            //
            // 
            self::$msgconcat .= "Error:" . PHP_EOL;
            $msg = '{"msg":"Error writing log [' . PATH_LOG . ']"}';
            self::$msgconcat .= $msg;
            self::$msgconcat .= "".PHP_EOL;
            
            die($msg);
        }

        return $this;
    }

    // 
    // 
    // 

    public function Show()
    {

       if(self::$show_msg_load) {

            print "\n";
            print self::$show_msg_load;
            print "\n";
        }
    }
}
