#!/bin/bash

tag=$1
date=$(date)

message_stash="Changement de version vers ${tag} le ${date}"

git add .
git stash -m $message_stash
git checkout $tag
rm -fr vendor
composer update
./vendor/bin/laminas bddadmin:update


