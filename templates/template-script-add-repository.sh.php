#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo ""
echo "Clone Repository!!"
echo ""
echo $(pwd)
echo ""

#
#
#
cd $(pwd)

#
#
#
if [ ! -d "{PATH}{BRANCH}" ]; then
echo "[Error Path]"
echo "Path {PATH}{BRANCH} does not exist!"
echo ""
exit 0
fi

echo "Entering: {PATH}{BRANCH}"
cd {PATH}{BRANCH}

#
#
#
echo ""
echo "git clone ssh://git@github.com/{GITUSER}/{REPOSITORY}.git";
git clone ssh://git@github.com/{GITUSER}/{REPOSITORY}.git
echo ""

echo "Entering the repository: {REPOSITORY}"

if [ ! -d "{PATH}{BRANCH}/{REPOSITORY}" ]; then
echo "[Error Path]"
echo "Path {PATH}{BRANCH}/{REPOSITORY} does not exist!"
echo ""
exit 0
fi

cd {PATH}{BRANCH}/{REPOSITORY}

#
#
#
echo ""
echo "git checkout -b {BRANCH}"
git checkout -b {BRANCH}

echo ""
echo "End GIT CLONE {REPOSITORY} SUCCESS!!"
echo ""
