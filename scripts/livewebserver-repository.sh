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
if [ ! -d "/home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta" ]; then
echo "[Error Path]"
echo "Path /home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta does not exist!"
echo ""
exit 0
fi

echo "Entering: /home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta"
cd /home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta

#
#
#
echo ""
echo "git clone ssh://git@github.com/jeffotoni/livewebserver.git";
git clone ssh://git@github.com/jeffotoni/livewebserver.git
echo ""

echo "Entering the repository: livewebserver"

if [ ! -d "/home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta/livewebserver" ]; then
echo "[Error Path]"
echo "Path /home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta/livewebserver does not exist!"
echo ""
exit 0
fi

cd /home/netcatc/sgidevel/repositorioSvn/gits3projetos/beta/livewebserver

#
#
#
echo ""
echo "git checkout -b beta"
git checkout -b beta

echo ""
echo "End GIT CLONE livewebserver SUCCESS!!"
echo ""
