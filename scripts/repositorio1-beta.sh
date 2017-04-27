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
cd "`pwd`"

#
#
#
echo "repositorio1"

#
#
#
cd {PATH}repositorio1


#
#
#
echo "checkout $BRANCH"


#
#
#
git checkout beta

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
git pull origin beta


echo "\End deploy!!"
echo " ------------ "
