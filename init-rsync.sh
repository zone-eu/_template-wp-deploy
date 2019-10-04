#!/usr/bin/env bash

# ensure we start from script directory and remember it
cd "${0%/*}"

echo "This script should be run only on first install (must be edited to run)."
echo ""
echo "Use with location of directory that contains uploads and static:"
echo "./init-rsync.sh virtXXXXX@hostname.tld:domeenid/www.domain.tld/prelive"

# comment this line to run
exit 1

if [[ ! ("$#" == 1) ]]; then
    echo "Please provide base for remote."
    exit 1
fi


[ "$#" -eq 1 ] || die "1 argument required, $# provided"

if [ "$1" = "help" ] && [ "$1" != "find-files-here" ]; then
	init-state
fi

$1 $2

cd ..

rsync -rcpEtvzh $1/uploads/ ./uploads/
rsync -rcpEtvzh $1/static/ ./static/

echo "Presumably done..."
