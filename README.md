# gitwebhooks

Gitwebhooks is a service for receiving GHub WebHooks.

Gitwebhooks is a microservice, it receives Http requests to be configured in addition to receiving requests from WebHooks.

Gitwebhooks receives a POST from WebHooks coming from Github, and it automatically generates a script from a template and runs it on the server.

Our example shows the class call executing all its methods: Handle the rules of WeHooks, generate script.sh, save the script to the disk, run it, remove the server script or not and generate log of the entire execution .

Our microservice has its own route controller and dynamic classes ie you will not need to make new there is an object that will dynamically mount them, using namespace and several other techniques to make it light and thin.

You can extend the entire application, just create your classes to apply them in your need.

When creating a class in web/src/MyControler/MyControlerClass.php for example, you can use it for example as follows:


```php

/**
 * 
 * $api->MyControlerClass()->MyMethod();
 *
 *              OR
 *
 * Use web\src\MyControler\MyControlerClass as MyCC;
 * 
 * $myobj = new MyCC();
 *
 *              OR
 *
 * $objMycc = new web\src\MyControler\MyControlerClass();
 *
 * // dependency injection
 * $objMycc->MyMethod($api);
 * 
 */

```

If you want to access any class of the system just pass $ api as a parameter and with it you can access any class and method available on the platform.

This way it is easier for you to create your own classes and apply them to the system


## Does not use library

The server is written in php and we do not use any library, the code is simple and very light, we know we can improve and extend the application even more and this is our goal.

We use psr2, autoloading, namespaces to write our server, our model instantiates class execution time with this technique we can instantiate classes and their methods in a light and practical way and with the possibility of doing dependency injection.

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


## Installing the program on the server

Our program is done in php, on your web server, can be running apache, ngnix or we can simply run the program with php -S localhost:9001

```sh

$ git clone ssh://git@github.com/jeffotoni/gitwebhooks.git

```

For this to work well you should do as www user as follows.

```sh

$ sudo -u www-data -H git clone -v ssh: //git@ggithub.com/jeffotoni/gitwebhooks.git

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

$ sudo -u www-data -H git clone -v ssh: //git@github.com/jeffotoni/gitwebhooks.git

$ sudo -u www-data -H chmod 755  -R Your directory

```

### If you are using apache, do not forget to enable in your virtualhosts [ AllowOverride All ]

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ test_index.php [QSA,L]

```

### If you are using nginx

```
server {
    listen 80;
    server_name yoursite.com;
    index index.php;
    error_log /path/to/yoursite.error.log;
    access_log /path/to/yoursite.access.log;
    root /path/to/public;

    location / {
        try_files $uri /index.php$is_args$args;
    }

    location ~ \.php {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param SCRIPT_NAME $fastcgi_script_name;
        fastcgi_index index.php;
        fastcgi_pass 127.0.0.1:9000;
    }
}

```

### If you are using PHP built-in server in production

```
$ php -S localhost:9001 -t public

```

### If you are using PHP built-in server in Locally

```
$ cd public
$ php -S localhost:9001 test_index.php

```

### Now just run "generate-config.php" so that your config is generated and you can configure it.

```
$ cd config
$ php -q generate-config.php

```

### The program will always check for the POST 
### received by GitHub to determine how it will deploy.

## System structure

```
 
 - public
    index.php
    test_index.php

 - config/
    generate-config.php (Run as soon as you put it on your server)

    setenv.conf.php

    setconfig.conf.php (Generated in real time by the program "generate-config.php")

    git.repositories.conf.php (In this file is where the paths of your git projects will be configured on your server)

    apache-rewritengine

    nginx-rewritengine
 
 - simulation/
    curl-test.sh (Executes the curl calls to test and simulate the production environment)

    github.webhooks.json (Json format)

    github.webhooks.form (x-www-form-urlencoded format)

    server-http.go (example to test a deploy using go)

    teste-template-deploy-golang.sh (A deploy to golang)
 
 - templates/
    simulation-example.sh.php (To perform the tests without actually executing)

    template-script-deploy.sh.php (This script we are using to deploy our applications in php)

    template-script-add-repository.sh.php (Template responsible for adding repository to your server)

    template-script-golang-deploy.sh.php

  - log/
     github-webooks.log (All actions done on gitwebhooks saved here)

  - web/src/
        Hooks/
            Handlers/
            Hooks/
            Http/
            Interfaces/
            Message/
                Logs

```

## cURL to perform the tests Locally

First under the application with php just to test as follows

```php

$ cd public
$ php -S localhost:9001 test_index.php

```

Now we can run local tests with curl

```sh

$ cd simulation
$ sh curl-test.sh

```

## curl-test.sh Source

You can simulate the submissions to your local machine as if it were github.


Sending post as if it were github, content type json, we made a file github.webhooks.json, 
which is in the same directory, to simulate the sending.

We are using in this shipment the secret "X-Hub-Signature"

```bash

curl -X POST \
     -H "Content-Type: application/json" \
     -H "X-Hub-Signature: sha1=9c714dcc8f1f4ba829c88fef184ccd0d090f019d" \
     -H "X-GitHub-Event: push" \
     -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
     http://localhost.gitwebhooks/github/webhooks \
     -d @github.webhooks.json

```

Sending post as if it were github, content type x-www-form-urlencoded, we made a 
file github.webhooks.form, which is in the same directory, to simulate the sending.

In this submission we are using a parameter in the url itself, "key", Discontinued use of the key

```bash

 curl -X POST \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -H "X-GitHub-Event: push" \
    -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
    http://localhost.gitwebhooks/github/webhooks\?key=b118eda467d926d003f9b4af9c203994 \
    -d @github.webhooks.form

```

Gitwebhooks is a service so we made a call to another url we created, to return the status of our server.

It is using an md5 "GitWebHoos-Authentication" authentication, but we can implement any of the "hash_hmac".

```bash

curl -X GET \
     -H "Content-Type: application/json" \
     -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
     http://localhost.gitwebhooks/gitwebhooks/status

```

When using the PHP built-in server the call can be made so

```bash

curl -X POST \
     -H "Content-Type: application/json" \
     -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
     -H "X-GitHub-Event: push" \
     -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
     http://localhost:9001/github/webhooks \
     -d @github.webhooks.json

```

Adding repository on server, it git clone on server.

```bash
curl -X GET \
     -H "Content-Type: application/json" \
     -H "GitWebHooks-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
     -H "GitWebHooks-Branch: master" \
     -H "GitWebHooks-GitUser: jeffotoni" \
     http://localhost:9001/webhooks/repository/add/s3designmania

```

## System Settings

We made an abstraction of our config, now we have a new file setenv.conf.php, it is responsible for generating our config, so your file setconfig.conf.php will be outside the git respository, and you can make your own configurations on the server without affecting It with updates, it will only be generated once.


## System Settings, setenv.conf.php

It is responsible for generating your config once, setconfig.conf.php

```php
/**
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

define("ROOT_DIR", PATHSET_LOCAL);

//
//
//
chdir(ROOT_DIR);

//
//
//

define("GITHUB_SECRET", "12345");


//
//
//

define("KEY", "b118eda467d926d003f9b4af9c203994");

//
//
//

define("GITWEBHOOKS_SECRET", "827ccb0eea8a706c4c34a16891f84e7b");

// 
// 
// 

define("PATH_LOG", ROOT_DIR. "log/github-webooks.log");

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

define("PATH_CLASS", "web/src");


// 
// 
// 

define("PATH_CLASS_NAMESPACE", "web\src");

//
//
//

define("TEMPLATE_DEPLOY", [

    "beta"        => "template-script-deploy",

    "test"        => "template-script-deploy",

    "product"     => "template-script-deploy",

    "repository"  => "template-script-add-repository",

    "simulation"  => "simulation-example",

    ]
   );

//
//
//

define("PATH_REPOSITORY", PATH_REPOSITORY_CREATE);

/** 
 *
 * or /var/www/gitmyprojects/
 *
 * example:
 * 
 * /var/www/gitmyprojects/beta
 * /var/www/gitmyprojects/product
 * /var/www/gitmyprojects/test
 *
 * /var/www/gitmyprojects/beta/gitproject1.git
 * /var/www/gitmyprojects/beta/gitproject2.git
 * 
 * /var/www/gitmyprojects/product/gitproject1.git
 * /var/www/gitmyprojects/product/gitproject2.git
 *
 * Configuring your paths
 * 
 * gitwebhooks      => /var/www/gitmyprojects
 *
 * gitproject1      => /var/www/gitmyprojects
 *
 * gitproject2      => /var/www/gitmyprojects
 *
 *  OR
 *  
 * gitwebhooks      => ../../../../../
 *
 * gitproject1      => ../../../../../
 *
 * gitproject2      => ../../../../../
 * 
 */

$ARRAY_PROJECT_GIT = parse_ini_file(PATH_REPOSITORY);

//
//
//

define("ARRAY_PROJECT_GIT", $ARRAY_PROJECT_GIT, true);

```

## Structure of the program

The $api object is special it can create and instantiate any class that is in the web/src/Hooks structure, so: $api = special object, WScript() = Class, LoadTemplate() == class method.

```php
/**
*
* @about project GitHub Webhooks, 
* Application responsible 
* for receiving posts from github webhooks, and automating 
* our production environment by deploying
* 
* @autor    @jeffotoni
* @date     25/04/2017
* @since  Version 0.1
*/

// 
// php -S localhost:9001 -t index.php
// 
// OR
// 
// php -S localhost:9001 -t test_index.php 
// 
// Apache .htaccess
// 
// OR
// 
// Ngnix
// 

require_once "../config/setenv.conf.php";

/** 
 * Various ways of instantiating objects
 *
 * Way one:
 * 
 * use web\src\Http\NewRouter;
 * $NewRouter = new NewRouter();
 *
 * Way two:
 *
 * $NewRouter = $api->NewRouter();
 */

//
// Class responsible for handling the responses
//

use web\src\Http\Response as Response;


//
// Class responsible for handling request 
//

use web\src\Http\Request as Request;

//
// Instantiating routes
//

$api->NewRouter()

    //
    //
    //

    ->Methods("POST")

    //
    //
    //

    ->HandleFunc(
        
        //
        // Defining your routes
        //

        '/github/webhooks', function (Response $response, Request $request) use ($api) {

            //
            // Class responsible for handling requests coming from github
            //

            $GitHub = $api->GitHub();

            $api->GitHub()

                //
                //
                // Authentication coming from github secret
                // For security reasons we suggest you use this option
                // 
                //

                ->AuthenticateSecretToken()


                //
                // Authentication of your key, coming from the URL, a GET
                //

                //->AuthenticateSecretKey()

                //
                // Authentication using md5, header
                //

                //->AuthenticateMd5()

                //
                // Setting the event
                //

                ->Event("push")

                //
                // Making the call to run the deploy scripts
                //

                ->WScript($api)

                // 
                // Loading and running updates
                // 
        
                ->LoadTemplate(
                    [

                    "REPOSITORY" => $GitHub::$REPOSITORY,

                    "PATH"       => ARRAY_PROJECT_GIT[$GitHub::$REPOSITORY],

                    "BRANCH"     => $GitHub::$BRANCH,

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
                //
                ->Execute()

                // 
                // Possibility to delete file, it is not mandatory
                //

                ->DelFile()

                // 
                // Generates log on disk so you can keep track of all executions
                //
                //
                ->LoadLog();

        }
    )

    //
    // It will execute the methods
    //

    ->Run();


//
// 
//

$api->NewRouter()

    //
    //
    //

    ->Methods("GET")

    //
    //
    //

    ->HandleFunc(
        '/webhooks/status', function (Response $response, Request $request) use ($api) {

            //
            //
            //

            $api->GitWebHooks()

            //
            //
            //

                ->AuthenticateMd5();

            //
            //
            //

            $arrayJson = [
            
            "status" => "online",
            ];

            $response->WriteJson($arrayJson);

        }

    )->Run();



//
// 
//

$api->NewRouter()

    //
    //
    //

    ->Methods("GET")

    //
    //
    //

    ->HandleFunc(
        '/webhooks/repository/add/{name}', function (Response $response, Request $request) use ($api) {


            //
            // Authentication
            // 

            $api->GitWebHooks()

                //
                //
                //
                ->AuthenticateMd5();

            //
            //
            //
            
            $branch     = $request->GetBranch();
            
            $repository = $request->GetName();

            $gitUser    = $request->GitUser();

            //
            //
            //
            
            $api->WScript()->AddRepository($gitUser, $repository, $branch);

        }
        
    )->Run();
    
```

## Riding our route, the method without comments


```php
$api->NewRouter()

    ->Methods("POST")

    ->HandleFunc("/github/webhooks", function (Response $response, Request $request) use ($api) {
            
            $GitHub = $api->GitHub();

            $api->GitHub()

                >AuthenticateSecretKey()

                ->Event("push")

                ->WScript($api)       

                ->LoadTemplate(
                    [

                    "REPOSITORY" => $GitHub::$REPOSITORY,

                    "PATH"       => ARRAY_PROJECT_GIT[$GitHub::$REPOSITORY],

                    "BRANCH"     => $GitHub::$BRANCH,

                    ]
                )          
                ->LoadFileScript()

                ->Save()

                ->Execute()

                ->DelFile()

                ->LoadLog();
        }
    )->Run();

```

## Structure of the Class WScript

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

namespace web\src\Hooks;


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

        if(isset($_ARRAY['BRANCH'], $_ARRAY['PATH']) && $_ARRAY['BRANCH'] && $_ARRAY['PATH']) {

            self::IsValidBranch($_ARRAY);

        } else {

            

            //
            //
            // 
            self::$msgconcat .= "Error: " . PHP_EOL;
            $msg = '{"msg":"Repository, branch and PATH Are mandatory, can not be empty"}';
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

            $content_config = $repository. ' = '.$path_projects.'';


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

       self::IsValidBranch($_ARRAY, true);

       //
       //
       //

       $modelo = "repository";

       $this->LoadTemplate($_ARRAY, $modelo)
         
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

    public function IsValidBranch($_ARRAY, $valid=false) {

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

        if($valid) {

            if(is_dir($is_repository_exist)) {

                //
                //
                // 
                self::$msgconcat .= "Created:" . PHP_EOL;
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
            }

        } else {

            if(!is_dir($is_repository_exist)) {

                //
                //
                // 
                self::$msgconcat .= "Created:" . PHP_EOL;
                $msg = '{"msg":"Repository '.$is_repository_exist.' does not exist!"}';
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
        $msgtmp = PHP_EOL . "--------------------------------------------------- START HERE --------------------------------------------------- " . PHP_EOL;
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

            //
            //
            //

            //$this->LoadLog();

            //
            //
            //
            
            die($msg);
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

```

## Template .sh

The template you can create your own, the way you want, you can assemble your vector to build it dynamically.

Standard Model:
```php
  [
     "REPOSITORY" => $GitHub::$REPOSITORY,

     "PATH"       => ARRAY_PROJECT_GIT[$GitHub::$REPOSITORY],

     "BRANCH"     => $GitHub::$BRANCH,
]

```
But you can extend exactly here what you want for your scripts

```php
[
   "REPOSITORY" => $GitHub::$REPOSITORY,

   "PATH" => $ARRAY_PROJECT_GIT [$GitHub::$REPOSITORY],

   "BRANCH" => $GitHub::$BRANCH,

   "CMD1" => "git checkout deploy",

   "CMD2" => "git merge master",

   "CMD3" => 'echo "Executing merge!"'
]

```

This is our template, but you can create it the way you want it, confore your real need.
We leave some examples.

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
