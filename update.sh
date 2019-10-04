#!/bin/bash
# for debug output, uncomment:
#set -x

# ensure we start from script directory
cd "${0%/*}"

# ensure no local changes affect our judgement...
git submodule init
git reset --hard
git clean -f
git submodule foreach git reset --hard
git submodule foreach git clean -f

git pull
git submodule update --remote
