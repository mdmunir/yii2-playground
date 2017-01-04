Yii 2 Piknikio
===============
# Directory Structure
```
app
    assets/              contains application assets such as JavaScript and CSS
    classes/             contains user defined classes for utilities
    commands/            contains console command classes
    config/              contains app configurations
    controllers/         contains Web controller classes
    mail/                mail template
    migrations/          migrations files
    models/              contains app-specific model classes
    runtime/             contains files generated during runtime
    views/               contains view files for the Web application
    web/                 contains the entry script and Web resources
    widgets/             Widgets
environments/            contains environment-based overrides. Example file to instalation
rest/                    REST API module.
task/                    Task module. Worker for queue process.
tests/                   Setingane malesi... Abaikan untuk saat ini.
vendor/                  contains dependent 3rd-party packages
```

# Langkah-langkah instalasi

* Clone, composer install
```
$ git clone https://gitlab.com/psmx/piknikio # or via ssh : git clone git@gitlab.com:psmx/piknikio.git 
$ cd piknikio
$ composer install --prefer-dist
$ php init --env=Development --overwrite=n
```

* Edit file `app/config/main-local.php`, ubah setingan untuk koneksi database.
* Lakukan migrasi database
```
$ php yii migrate
```

Setelah selesai, aplikasi dapat diakses dari `http://localhost/piknikio/app/web/index.php`
