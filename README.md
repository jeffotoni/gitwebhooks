# gitwebhooks

The program that runs on the server, to deploy applications, we are using php, but can be done with any deploy language.

The program receives a POST from github's WebHooks, so you can generate your script from a template and run it as you need it.

Our example shows the call of the class executing all its methods: Treat the rules of WeHooks, Generate script.sh, save the script to disk, execute it, remove server script or not and generate log of all execution.

## Used libraries:

The server is written in php and we do not use any library, the code is simple and very light, we know we can improve and extend the application even more and this is our goal.

We use psr2, autoloading, namespaces to write our server, our model instantiates class execution time with this technique we can instantiate classes and their methods in a light and practical way and with the possibility of doing Injection.

## Git Server Environment

We know there are hundreds of solutions for implementations, each satisfying a need and reality.

Before developing a simple and lightweight deployment solution for my need, I ran several tests with git hooks, bitbucket webhooks, gitlab, and github.

Git Hooks is interesting, if you have no problem keeping multiple remote servers is a valid option. To create a git server using the hooks is very simple and practical.

To know more about git hooks is my reference.

[Git (Hooks)](https://git-scm.com/book/gr/v2/Customizing-Git-Git-Hooks)

WebHooks is the feature that tools like github, gitlab and bitbucket make available so that we can have a notification for each event in our repository, the events are diverse, all are programmable. You set up the URL that will point to your server, the webhooks sends a POST to your URL, with all the information you need so that you can automate your development process.

To know a little more about Webhhoks here are the references I've used:

[Github (Webhooks)](https://developer.github.com/webhooks/)

[Gitlab (Webhooks)](https://docs.gitlab.com/ce/user/project/integrations/webhooks.html)

[Bitbucket (Webhooks)](https://bitbucket.org/StephenHowells/webhook)

Our program will receive a POST from Github, the event being what we programmed it will generate a script from a template that we define for our deploy and execute it on our server.

Creating SSH Keys and using git clone on our remote server

For everything to work, we suggest that you use ssh: // to do the git clone, this way we guarantee that when we run the git commands as www user we will not have to worry about the system request password or user, although we have to cache but Ssh is still the best option for good health of your server.

```sh

$ git clone ssh: //git@github.com/username/repository.git

```

For this to work well you should do as www user as follows.

```sh

$ sudo -u www-data -H git clone -v ssh: //git@github.com/username/repository.git

```

For this to work you will have to do the following steps.

```sh

$ sudo -u www-data mkdir /var/www/.ssh

$ sudo -u www-data ssh-keygen -t rsa -b 4096 -C "your_email@example.com"

```

If you want a good reference of a checked here

[Generate SSH Keys (SSH)](https://help.github.com/articles/connecting-to-github-with-ssh)


It will generate the id_rsa, id_rsa.pub, in /var/www/.ssh now just paste your public key and paste it into github settings -> keys -> new SSH Key

Copying the Public Key

```sh

$ sudo -u www-data cat /var/www/.ssh/id_rsa.pub

```

An example of a structure you might create on your server so that our program works correctly

```sh

$ mkdir /var/www/githtml/beta/repository.git

$ mkdir /var/www/githtml/product/repository.git

```

Now that we have configured our access keys we can use the git clone

```sh

$ sudo -u www-data -H git clone -v ssh: //git@github.com/username/repository.git

$ sudo -u www-data -H chmod 755  -R Your directory

```

### The program will always check for the POST 
### received by GitHub to determine how it will deploy.

## System Settings

```php
/*
*
* @about project GitHub Webhooks, 
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

define("PATH_LOCAL", getcwd() . "/");


// 
// 
// 

define("PATH_LOG", PATH_LOCAL. "log/github-webooks.log");

// 
// 
// 

define("PATH_TEMPLATE", "templates/");

// 
// 
// 

define("PATH_SCRIPT", "scripts/");

// 
// 
// 

//
//
//

define("TEMPLATE_DEPLOY", [

    "beta"        => "template-script-deploy",
    "test"        => "template-script-deploy",
    "product"     => "template-script-deploy",
    "simulation"  => "simulation-example",

    ]
   );

define("ARRAY_PROJECT_GIT", [

    "gitwebhooks"        => "../../../",
    "s3archiviobrasil"     => "../../../../",
    "s3rafaelmendonca"    => "../../../../",

    ]
);

```

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

/*
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

This template is for deploy in a golang project


```sh
#!/bin/bash
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nDeploy Go(Golang) .. Being done!!"

#
#
cd `pwd`

#
#
echo "{REPOSITORY}"

#
#
cd {PATH}{REPOSITORY}

#
#
echo "checkout $BRANCH"

#
#
#git checkout {BRANCH}

#
#
git reset --hard HEAD

#
#
echo "Starting pull.."

#
#
git pull origin {BRANCH}

#
# stop process, id kill of program
#
echo "\nKill all Process program!!"

#
#
#
for pid in $(ps -fe | grep {PROGRAM} | grep -v grep | awk '{print $2}'); do

    if [ "$(echo $pid | grep "^[ [:digit:] ]*$")" ] 
        then

        kill -9 "$pid"
        echo "\nKill [$pid]" 
    fi
done

#
#
#
for pid2 in $(ps -C {PROGRAM} -o pid 2>/dev/null); do


if [ "$(echo $pid2 | grep "^[ [:digit:] ]*$")" ]
    then
    kill -9 $pid2
    echo "\nkill [$pid2]"
fi
done

echo "\nDone!!!"

echo "go build {PROGRAM}.go"
go build "{PROGRAM}.go"

#echo "go install {PROGRAM}"
#go install "{PROGRAM}.go"

echo "\nExecute {PROGRAM}"
exec ./{PROGRAM}

echo "\End deploy!!"
echo " ------------ "


```
