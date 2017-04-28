# gitwebhooks

The program that runs on the server, to deploy applications, we are using php, but can be done with any deploy language.

The program receives a POST from github's WebHooks, so you can generate your script from a template and run it as you need it.

Our example shows the call of the class executing all its methods: Treat the rules of WeHooks, Generate script.sh, save the script to disk, execute it, remove server script or not and generate log of all execution.

## Used libraries:

The server is written in php and we do not use any library, the code is simple and very light, we know we can improve and extend the application even more and this is our goal.

We use psr2, autoloading, namespaces to write our server, our model instantiates class execution time with this technique we can instantiate classes and their methods in a light and practical way and with the possibility of doing Injection.

## Git Server Environment

We know there are many hundreds of solutions for deploys, each one meets a need and reality.

Before developing a simple and light deploy solution for my need, I did several tests with git hooks, bitbucket webhooks, gitlab and github.

Git Hooks is interesting, if you have no problem keeping multiple remote in your branch in git is a valid option. To create a git server using the hooks is very simple and practical.

To learn more how to create a git hooks server click here.

WebHooks is the feature that the tools make available so that we can have a notification for each event in our repository, the events are diverse, all are programmable. You configure the URL that will point to your server, the webhooks sends a POST to your URL, with all the information you need so you can automate your development process.

To know a little more about Webhhoks here are the references I used:

[Github (Webhooks)](https://developer.github.com/webhooks/)

[Gitlab (Webhooks)](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html)

[Bitbucket (Webhooks)](https://bitbucket.org/StephenHowells/webhook)


## Structure of the program

The $api object is special it can create and instantiate any class that is in the web/src/Hooks structure, so: $api = special object, WScript() = Class, LoadTemplate() == class method.

```php
        // 
        // 
        //

        $api->WScript()->LoadTemplate(
            [

            "REPOSITORY" => $REPOSITORY,
            "PATH"       => $ARRAY_PROJECT_GIT[$REPOSITORY],
            "BRANCH"     => $BRANCH,
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
        	
        ->Execute(false) // false => simule | true execute your script template

        // 
        // Possibility to delete file, it is not mandatory
        //

          ->DelFile()

        // 
        // Generates log on disk so you can keep track of all executions
        //

        ->LoadLog();
    
```

## Structure of the Class WScript

```php
*
* @about    project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor    @jeffotoni
* @date     25/04/2017
* @since    Version 0.1
* 
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
        // code...
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

        $path = PATH_LOCAL;

        // 
        // 
        // 

        $modeloName = isset(self::GetTemplate()[$modelo]) ? self::GetTemplate()[$modelo] : "beta";

        // 
        // 
        // 

        $file_template = "{$path}/" . PATH_TEMPLATE . "{$modeloName}.sh.php";

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

            $PATH_SCRIPT = PATH_LOCAL . self::$pathScript;

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

            self::$msg = "Saved successfully!";

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

    public function DelFile(){

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

    public function LoadLog()
    {

        //
        //
        //

        self::$show_msg_load = date("Y-m-d [H:i]") . " - " . self::$msgconcat . PHP_EOL;

        //
        //
        //

        file_put_contents(PATH_LOG, self::$show_msg_load, FILE_APPEND);

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


```

## Template .sh

The template you can create your own, the way you want, you can assemble your vector to build it dynamically.

Standard Model:
```php
  [
    "REPOSITORY" => $ REPOSITORY,
    "PATH" => $ ARRAY_PROJECT_GIT [$ REPOSITORY],
    "BRANCH" => $ BRANCH,
]

```
But you can extend exactly here what you want for your scripts

```php
[
   "REPOSITORY" => $REPOSITORY,
   "PATH" => $ARRAY_PROJECT_GIT [$ REPOSITORY],
   "BRANCH" => $BRANCH,
   "CMD1" => "git checkout deploy",
   "CMD2" => "git merge master",
   "CMD3" => 'echo "Executing merge!"'
]

```

This is our template, but you can create it the way you want it, confore your real need

```sh
#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nDeploy being done!!"

#
#
#
cd `pwd`

#
#
#
echo "{REPOSITORY}"

#
#
#
cd {PATH}{REPOSITORY}


#
#
#
echo "checkout $BRANCH"


#
#
#
git checkout {BRANCH}

#
#
git reset --hard HEAD


#
#
#
echo "Starting pull.."

#
#
#
git pull origin {BRANCH}


echo "\End deploy!!"
echo " ------------ "

```
