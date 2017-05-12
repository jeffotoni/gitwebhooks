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
echo "Deploy being done!!"

echo "You are in:"
echo $(pwd)

#
#
#
cd $(pwd)

#
#
#
echo "{PATH}{BRANCH}/{REPOSITORY}"

if [ ! -d "{PATH}{BRANCH}/{REPOSITORY}" ]; then
echo "[Error Path]"
echo "Path {PATH}{BRANCH}/{REPOSITORY} does not exist!"
echo ""
exit 0
fi

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
#echo ""
#echo "git reset --hard HEAD"
#git reset --hard HEAD
#git reset --soft HEAD

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
