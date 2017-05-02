#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nDeploy being done!!"


echo "\nYou are in:"
echo $(pwd)

#
#
#
cd $(pwd)

#
#
#
echo "{BRANCH}/{REPOSITORY}"

#
#
#
cd {PATH}{BRANCH}/{REPOSITORY}

echo "-----------------------------------------------------"
echo "Entering the repository"
echo $(pwd)
echo "-----------------------------------------------------"

#
#
#
echo "checkout {BRANCH}"

#
#
#
git checkout {BRANCH}

#
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
echo " @@@@@@@@@@@@@@@@ "
echo " ------------------ "
