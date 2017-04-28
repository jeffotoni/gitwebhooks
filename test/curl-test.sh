#!/bin/bash

#
# autor: @jeffotoni
# about: Script to deploy our applications
# date:  25/04/2017
# since: Version 0.1
#
#

# -d @github.webhooks.json \
# -v \
# -H "Content-Type: application/x-www-form-urlencoded" \
# -H 'Content-Type: application/json' \

curl -X POST \
	 -H "Content-Type: application/x-www-form-urlencoded" \
	 localhost:9001/\?key=b118eda467d926d003f9b4af9c203994 \
	 -d @github.webhooks.json