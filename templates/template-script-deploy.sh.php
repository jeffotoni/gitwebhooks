#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo ""
echo "#################################### start deploy #################################### "
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

echo ""
echo "Entering the repository"
echo {PATH}{BRANCH}/{REPOSITORY}
echo ""
echo "Path Local"
echo $(pwd)
echo ""

#
#
#

echo "git checkout {BRANCH}"
echo ""

#
#
#
git checkout {BRANCH}

#
#
#
echo ""
echo "git reset --hard HEAD"
git reset --hard HEAD


#
#
#
echo "Starting pull !"
echo ""
#
#
#
git pull origin {BRANCH}

echo ""
echo "End deploy!!"
echo ""
