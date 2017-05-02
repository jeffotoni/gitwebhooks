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

# curl -X POST \
#	 -H "Content-Type: application/x-www-form-urlencoded" \
#	 -H "X-GitHub-Event: push" \
#	 -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
#	 localhost:9001/\?key=b118eda467d926d003f9b4af9c203994 \
#	 -d @github.webhooks.json

# curl -X POST \
# 	 -H "Content-Type: application/x-www-form-urlencoded" \
# 	 -H "X-GitHub-Event: push" \
#	 -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
# 	 http://localhost/gitwebhooks/github/webhooks\?key=b118eda467d926d003f9b4af9c203994 \
# 	 -d @github.webhooks.form

# curl -X POST \
# 	 -H "Content-Type: application/json" \
# 	 -H "X-GitHub-Event: push" \
# 	 -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
# 	 http://localhost/gitwebhooks/github/webhooks\?key=b118eda467d926d003f9b4af9c203994 \
# 	 -d @github.webhooks.json
	 
# curl -X GET \
# 	 -H "Content-Type: application/json" \
# 	 -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
# 	 http://localhost:9001/gitwebhooks/status

# curl -X GET \
# 	 -H "Content-Type: application/json" \
# 	 -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
# 	 http://localhost/gitwebhooks/webhooks/status

# We suggest that you take the test directly from github, because 
# it takes the contents that come from POST to calculate the key, 
# and our json is changed to the tests.
# 
# This authentication does not need to pass by getting
#
#
# curl -X POST \
# 	 -H "Content-Type: application/json" \
# 	 -H "X-Hub-Signature: sha1=9c714dcc8f1f4ba829c88fef184ccd0d090f019d" \
# 	 -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
# 	 -H "X-GitHub-Event: push" \
# 	 -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
# 	 http://localhost/gitwebhooks/github/webhooks \
# 	 -d @github.webhooks.json
	
# curl -X GET \
# 	 -H "Content-Type: application/json" \
# 	 -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
# 	 http://localhost:9001/webhooks/status

#sleep 2

# curl -X POST \
# 	 -H "Content-Type: application/json" \
# 	 -H "GitWebHoos-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
# 	 -H "X-GitHub-Event: push" \
# 	 -H "X-GitHub-Delivery: e4cd4180-2c67-11e7-8099-87e86dbb4105" \
# 	 http://localhost:9001/github/webhooks \
# 	 -d @github.webhooks.json
# 	

curl -X GET \
	 -H "Content-Type: application/json" \
	 -H "GitWebHooks-Authentication: md5=827ccb0eea8a706c4c34a16891f84e7b" \
	 -H "GitWebHooks-Branch: beta" \
	 -H "GitWebHooks-GitUser: jeffotoni" \
	 http://localhost:9001/webhooks/repository/add/yourprojectgit