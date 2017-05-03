#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "We're just pretending"
echo ""
#
#
#
cd $(pwd)

#
#
#
echo "You are in"

#
#
#
echo $(pwd)

#
#
#
echo "{REPOSITORY}"

#
#
#

if [ ! -d "{PATH}{REPOSITORY}" ]; then
echo "[Error Path]"
echo "Path {PATH}{REPOSITORY} does not exist!"
echo ""
exit 0
fi

cd {PATH}{REPOSITORY}

#
#
#
echo "git branch"

#
#
#
git branch

#
#
#
echo "git log"

#
#
#
git log
