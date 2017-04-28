#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nDeploy Go(Golang) .. Being done!!"

#
#
cd `pwd`

#
#
echo "{REPOSITORY}"

#
#
cd {PATH}{REPOSITORY}

#
#
echo "checkout $BRANCH"

#
#
#git checkout {BRANCH}

#
#
git reset --hard HEAD

#
#
echo "Starting pull.."

#
#
git pull origin {BRANCH}

#
# stop process, id kill of program
#
echo "\nKill all Process program!!"

#
#
#
for pid in $(ps -fe | grep {PROGRAM} | grep -v grep | awk '{print $2}'); do

	if [ "$(echo $pid | grep "^[ [:digit:] ]*$")" ] 
		then

		kill -9 "$pid"
    	echo "\nKill [$pid]" 
	fi
done

#
#
#
for pid2 in $(ps -C {PROGRAM} -o pid 2>/dev/null); do


if [ "$(echo $pid2 | grep "^[ [:digit:] ]*$")" ]
	then
	kill -9 $pid2
	echo "\nkill [$pid2]"
fi
done

echo "\nDone!!!"

echo "go build {PROGRAM}.go"
go build "{PROGRAM}.go"

#echo "go install {PROGRAM}"
#go install "{PROGRAM}.go"

echo "\nExecute {PROGRAM}"
exec ./{PROGRAM}

echo "\End deploy!!"
echo " ------------ "
