#!/bin/bash

#
# @autor 	@jeffotoni
# @about    Script to deploy our applications
# @date 	25/04/2017
# @since    Version 0.1
#

echo "\nDeploying"

##
# PATH REPOSITORY
echo $GIT_PATH


#
#
#
cd $GIT_PATH

#
#
git checkout beta

#
#
git reset --hard HEAD

#
#
git pull

echo "\End deploy!!"
echo " ------------ "
