#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nDeploying"

#
#
#
cd `pwd`
cd ../../{REPOSITORY}

#
#
git checkout {BRANCH}

#
#
git reset --hard HEAD

#
#
git pull origin {BRANCH}


echo "\End deploy!!"
echo " ------------ "
