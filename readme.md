Requires existing wp-cli in path.
Repos from github are used - requires that user's key is valid in github.

Just a sample edit

## INSTALLING
+ create database & user
+ import database
+ clone [...]-wp-deploy
+ execute following
```
//will init and load submodules, create working WP install (needs DB credentials) etc,
./init.sh

//will install needed third party plugins and removes not needed default themes
./init-wp-plugins.sh

```
+ replace URLs in database using wp-cli

### Expected directory tree:

+ [...]/someplace/wordpress - docroot, will be created by init if missing
+ [...]/someplace/[...]-wp-deployment - this repo

will abort if ../wordpress is not empty.

All components to be installed and updates to be done using wp-cli.

## UPDATING
```

// does all the work needed for repos
$ ./update.sh

//does WP core, plugins, themes and languages update (using wp-cli, for components installed directly)
$ ./update-wp.sh

```


## When moving to live:

```
$ wp search-replace "https://dev.site.tld" "https://site.tld" --network
```

edit local-config.php for site URL


## When moving to staging:
change site names in sites, blogs tables
```
$ wp search-replace "https://site.tld" "https://dev.site.tld" --network
```


If needed for multisite per-site operations use "url" option:
--url=https://somesite.site.tld
