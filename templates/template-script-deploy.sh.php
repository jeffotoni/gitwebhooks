#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo ""
echo "#################################### start #################################### "
echo ""
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
echo {PATH}{BRANCH}/{REPOSITORY}
echo ""
echo "Path Local"
echo $(pwd)
echo "-----------------------------------------------------"

#
#
#
echo ""
echo "-----------------------------------------------------"
echo "checkout {BRANCH}"
echo "-----------------------------------------------------"
echo ""
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
