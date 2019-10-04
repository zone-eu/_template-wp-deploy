#!/bin/bash
# for debug output, uncomment:
#set -x

# ensure we start from script directory and remember it
cd "${0%/*}"

dir="$(pwd)"
base="$(basename "$dir")"

if [ "$(ls -A ../wordpress)" ]; then
     echo "../wordpress folder not empty - aborting initialization, please configure manually!"
     exit 1
fi

#git clone git@bitbucket.org:tehnokratt/_template-wp-deploy.git

# update gitmodules etc
"$dir/update.sh"

# prepare WP install and folders for static content

mkdir -p "$dir/../wordpress"
mkdir -p "$dir/../uploads"
mkdir -p "$dir/../static"

cd "$dir/../wordpress"
wp core download
cp "$dir/wp-config.php" .
cp "$dir/local-config.php" .
cp "$dir/.htaccess" .htaccess
cp "$dir/favicon.ico" .

mkdir -p ./wp-content/mu-plugins
mkdir -p ./wp-content/languages/plugins
mkdir -p ./wp-content/languages/themes

cp "$dir/.htaccess-wp-content" ./wp-content/.htaccess

"$dir/init-symlinks.sh"

echo "
WP has been prepared!

If installing development environment please rsync uploads and static files (documents) from  live.

Please edit local-config.php and add correct DB credentials, then import DB and run replace:
wp db import somedbname.sql
wp search-replace 'prelive.domain.tld' 'domain.tld' --network

Then move back to deployment folder and install plugins available from wordpress.org:
./init-wp-plugins.sh
"
