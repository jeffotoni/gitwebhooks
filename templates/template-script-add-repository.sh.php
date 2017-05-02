#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "cd beta";
echo "sudo -u www-data -H git clone ssh://git@github.com/jeffotoni/{REPOSITORY}.git\n";
echo "sudo -u www-data -H git checkout beta\n";

echo "cd producao";
echo "sudo -u www-data -H git clone ssh://git@github.com/jeffotoni/{REPOSITORY}.git\n";
echo "sudo -u www-data -H git checkout product\n";


echo "\nClone Repository!!"

echo $(pwd)

#
#
#
cd $(pwd)

#
#
#
echo "{PATH}{BRANCH}/{REPOSITORY}"

#
#
#
cd {PATH}{BRANCH}


echo "git clone ssh://git@github.com/{GITUSER}/{REPOSITORY}.git";

#
#
#
git clone ssh://git@github.com/{GITUSER}/{REPOSITORY}.git

#
#
#
echo "checkout -b {BRANCH}"


#
#
#
git checkout -b {BRANCH}


echo "\End GIT CLONE {REPOSITORY} SUCCESS!!"

echo " @@@@@@@@@@@@@@@@ "

echo " ------------------ "
