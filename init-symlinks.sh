#!/bin/bash
# for debug output, uncomment:
#set -x

#
# Create all required symlinks - forcing overwrite or (plugins, themes, translations) unlinking and re-linking
#

# ensure we start from script directory and remember it
cd "${0%/*}"

dir="$(pwd)"
base="$(basename "$dir")"

# docroot - managed non-wordpress components, force overwrite

cd "$dir/../wordpress"
find ../$base/src/docroot/* -maxdepth 0 -exec ln -sf {} \;

# static files under docroot - managed via FTP

cd "$dir/../wordpress"
find ../static/* -maxdepth 0 -exec ln -s {} \;

# wp-content-uploads - outside deployment, to facilitate safe reinstalls

cd "$dir/../wordpress/wp-content"
ln -s ../../uploads

# wp-content - possibly sunrise.php, force overwrite

cd "$dir/../wordpress/wp-content"
find ../../$base/src/wp-content/* -maxdepth 0 -exec ln -sf {} \;

# plugins

cd "$dir/../wordpress/wp-content/plugins"
find -type l -delete
find ../../../$base/src/plugins/* -maxdepth 0 -exec ln -s {} \;

# themes

cd "$dir/../wordpress/wp-content/themes"
find -type l -delete
find ../../../$base/src/themes/* -maxdepth 0 -exec ln -s {} \;

# mu-plugins

cd "$dir/../wordpress/wp-content/mu-plugins"
find -type l -delete
find ../../../$base/src/mu-plugins/* -maxdepth 0 -exec ln -s {} \;

# translations - plugins

cd "$dir/../wordpress/wp-content/languages/plugins"
find -type l -delete
find ../../../../$base/src/languages/plugins/* -maxdepth 0 -exec ln -s {} \;

# translations - themes

cd "$dir/../wordpress/wp-content/languages/themes"
find -type l -delete
find ../../../../$base/src/languages/themes/* -maxdepth 0 -exec ln -s {} \;
