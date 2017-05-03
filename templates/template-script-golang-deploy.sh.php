#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "Deploy Go(Golang) .. Being done!!"

#
#
echo $(pwd)
cd $(pwd)

#
#
echo "{REPOSITORY}"

#
#
if [ ! -d "{PATH}/{REPOSITORY}" ]; then
echo "[Error Path]"
echo "Path does not exist!"
echo ""
exit 0
fi

cd {PATH}{REPOSITORY}

#
#
echo "checkout $BRANCH"

#
#
#git checkout {BRANCH}

#
#
echo "git reset --hard HEAD"

git reset --hard HEAD

#
#
echo "git pull origin {BRANCH}"

#
#
git pull origin {BRANCH}

#
# stop process, id kill of program
#
echo "Kill all Process program!!"

#
#
#
for pid in $(ps -fe | grep {PROGRAM} | grep -v grep | awk '{print $2}'); do

	if [ "$(echo $pid | grep "^[ [:digit:] ]*$")" ] 
		then

		kill -9 "$pid"
    	echo "Kill [$pid]" 
	fi
done

#
#
#
for pid2 in $(ps -C {PROGRAM} -o pid 2>/dev/null); do


if [ "$(echo $pid2 | grep "^[ [:digit:] ]*$")" ]
	then
	kill -9 $pid2
	echo "kill [$pid2]"
fi
done

echo "Done!!!"

echo "go build {PROGRAM}.go"
go build "{PROGRAM}.go"

#echo "go install {PROGRAM}"
#go install "{PROGRAM}.go"

echo "Execute {PROGRAM}"
exec ./{PROGRAM}

echo "\End deploy!!"
echo " ------------ "
