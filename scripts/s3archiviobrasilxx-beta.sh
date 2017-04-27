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
cd ../
cd s3archiviobrasilxx

#
#
git checkout beta

#
#
git reset --hard HEAD

#
#
git pull origin beta


echo "\End deploy!!"
echo " ------------ "
