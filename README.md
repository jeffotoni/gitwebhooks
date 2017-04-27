# gitwebhooks

The program that runs on the server, to deploy applications, we are using php, but can be done with any deploy language.

The program receives a POST from github's WebHooks, so you can generate your script from a template and run it as you need it.

Our example shows the call of the class executing all its methods: Treat the rules of WeHooks, Generate script.sh, save the script to disk, execute it, remove server script or not and generate log of all execution.

## Used libraries:

The server is written in php and we do not use any library, the code is simple and very light, we know we can improve and extend the application even more and this is our goal.

We use psr2, autoloading, namespaces to write our server, our model instantiates class execution time with this technique we can instantiate classes and their methods in a light and practical way and with the possibility of doing Injection.

* [Github, Gitlab or Bitbucket ?](#gitseveral)

* [Setting up our server to deploy](#deploy)

* [Why do not we use git hooks?](#githooks)

* [Why choose Webhooks?](#webhooks)

* [Configuring Access Keys](#acesskey)

* [ssh, http ou https?](#sshttp)

* [Configure ssh github](#github)

* [Configure ssh server](#gitserver)

* [Structure](#structure)


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
