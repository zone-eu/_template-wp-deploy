#!/usr/bin/env bash
# for debug output, uncomment:
#set -x

# ensure we start from script directory and remember it
cd "${0%/*}"

# ensure we have all the variables

[ ! -f .env ] && echo "Please make provide configuration in .env, see .env.example ... for example :-)" && exit 1

source .env

[ -z "$ZONE_WP_PROD_HOME" ] && echo "ZONE_WP_PROD_HOME not configured in .env" && exit 1
[ -z "$ZONE_WP_PRELIVE_HOME" ] && echo "ZONE_WP_PRELIVE_PATH not configured in .env" && exit 1
[ -z "$ZONE_WP_PRELIVE_SSH" ] && echo "ZONE_WP_PRELIVE_SSH not configured in .env" && exit 1
[ -z "$ZONE_WP_PRELIVE_PATH" ] && echo "ZONE_WP_PRELIVE_PATH not configured in .env" && exit 1

rsync -rcpEtvzh --delete ../uploads/ $ZONE_WP_PRELIVE_SSH:$ZONE_WP_PRELIVE_PATH/uploads/

rsync -rcpEtvzh --delete ../static/ $ZONE_WP_PRELIVE_SSH:$ZONE_WP_PRELIVE_PATH/static/

wp db export ../live-dump.sql --path=../wordpress
wp db export ../prelive-dump.sql --ssh=$ZONE_WP_PRELIVE_SSH$ZONE_WP_PRELIVE_PATH/wordpress/
wp db reset --ssh=$ZONE_WP_PRELIVE_SSH$ZONE_WP_PRELIVE_PATH/wordpress/

rsync -rcpEtvzh ../live-dump.sql $ZONE_WP_PRELIVE_SSH:$ZONE_WP_PRELIVE_PATH/live-dump.sql

wp db import ../live-dump.sql --ssh=$ZONE_WP_PRELIVE_SSH$ZONE_WP_PRELIVE_PATH/wordpress/
wp search-replace $ZONE_WP_PROD_HOME $ZONE_WP_PRELIVE_HOME --ssh=$ZONE_WP_PRELIVE_SSH$ZONE_WP_PRELIVE_PATH/wordpress/

echo "Presumably done..."
