#!/bin/bash
cd "${0%/*}"

cd ../wordpress

function wp-cron {
    wp cron event run --due-now --quiet
}

function wp-cron-multisite {
    site_urls=$(wp site list --field=url)

    for site_url in $site_urls
    do
        wp cron event run --due-now  --url="$site_url" --quiet
    done
}

# call: wp-cron or wp-cron-multisite
wp-cron
