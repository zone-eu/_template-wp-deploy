#!/bin/bash
# for debug output, uncomment:
#set -x

# ensure we start from script directory
cd "${0%/*}"

dir="$(pwd)"
base="$(basename "$dir")"

cd ../wordpress

# must be done here to avoid db-related errors
wp config shuffle-salts

while read in; do wp plugin install "$in" --force; done < ../$base/plugins.txt

# remove un-needed components

wp plugin delete hello

wp theme delete twentyfifteen
wp theme delete twentysixteen
