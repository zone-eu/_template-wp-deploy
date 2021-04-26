Requires existing wp-cli in path.
Repos from github are used - requires that user's key is valid in github.

## INSTALLING
+ create database & user
+ `git clone [...]-wp-deploy`
+ copy & edit `./conf/.env.example`   
+ execute following to init and load submodules, create working WP install, install pludins etc
```

./handler init some-db-dump.sql

```
+ replace URLs in database using wp-cli (command sample provided after running init)
+ get contents for `wp-content/uploads` via rsync or other method:
``` 

rsync -rcpEtvzh virtXXXXX@example:/dataXX/virtXXXX/domeenid/www.example.com/prelive/uploads/ ../uploads/

```

### Expected directory tree:

+ `[...]/someplace/[...]-wp-deployment` - this repo
+ `[...]/someplace/wordpress` - docroot, will be created by init if missing
+ `[...]/someplace/uploads` - uploads, will be created by init if missing & symlinked
+ `[...]/someplace/static` - contents will be symlinked to docroot, for example managed via FTP

will abort if ../wordpress is not empty.

All components to be installed and updates to be done using wp-cli.

## CRON
```

# run cron for standalone WP
./handler wp-cron

# run cron for all WP multisite sites
./handler wp-cron-multisite

```

## UPDATING
```

# does all the work needed for repos
./handler update

# does WP core, plugins, themes and languages update
./handler update-wp

```

## SYNCING

Prelive and dev environment can be configured in .env for easy sync.

```

./handler sync-prelive
./handler sync-dev

```