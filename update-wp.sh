#!/bin/bash
# for debug output, uncomment:
#set -x

cd "${0%/*}"

cd ../wordpress

wp core update
wp plugin update --all
wp theme update --all
wp core language update
