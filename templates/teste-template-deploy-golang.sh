#!/bin/bash
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#

echo "\nKill all Process program!!"

PROGRAM="hello"

for pid in $(ps -fe | grep $PROGRAM | grep -v grep | awk '{print $2}'); do

	if [ "$(echo $pid | grep "^[ [:digit:] ]*$")" ] 
		then

		kill -9 "$pid"
    	echo "\nKill [$pid]" 
	fi
done

# echo "ps -C $PROGRAM -o pid "
for pid2 in $(ps -C $PROGRAM -o pid 2>/dev/null); do


if [ "$(echo $pid2 | grep "^[ [:digit:] ]*$")" ]
	then
	kill -9 $pid2
	echo "\nkill [$pid2]"
fi
done

echo "\nDone!!!"

echo "go build $PROGRAM.go"
go build "$PROGRAM.go"

#echo "go install $PROGRAM"
#go install "$PROGRAM.go"

echo "\nExecute $PROGRAM"
exec ./$PROGRAM

