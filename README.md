# Extended lang translations for Laravel 5.3+

Translation support for 69 languages.

![lang translations](https://user-images.githubusercontent.com/10347617/40197728-f289d00c-5a1c-11e8-877a-7ac379ceb4a2.png)

<p align="center">
    <a href="https://styleci.io/repos/132602203"><img src="https://styleci.io/repos/132602203/shield" alt="StyleCI" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://img.shields.io/packagist/dt/andrey-helldar/lang-translations.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="https://travis-ci.org/andrey-helldar/lang-translations"><img src="https://travis-ci.org/andrey-helldar/lang-translations.svg?branch=master" alt="Travis CI" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/license?format=flat-square" alt="License" /></a>
</p>


## Installation

To get the latest version of Lang Translations Library, simply `require` and `require-dev` the project using [Composer](https://getcomposer.org):

```
$ composer require andrey-helldar/lang-translations --dev
$ composer require andrey-helldar/pretty-array
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/pretty-array": "^1.0"
    },
    "require-dev": {
        "andrey-helldar/lang-translations": "^2.0"
    }
}
```

For using package in the Laravel 5.3-5.5 (php >= 5.6.0 <=7.1.2), use `^1.0` version.

For using package in the Laravel 5.5-7.x (php >= 7.1.3), use `^2.0` version (default).


If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
Helldar\LangTranslations\ServiceProvider::class,
```


## Using

### Important

The package replaces only certain files in your lang directories:

    resources/lang/<lang>/auth.php
    resources/lang/<lang>/buttons.php
    resources/lang/<lang>/errors.php
    resources/lang/<lang>/forms.php
    resources/lang/<lang>/statuses.php
    resources/lang/<lang>/titles.php
    
    // or    
    resources/lang/<lang>.json

He does not touch any other files.

When updating, the package reads your changes in the files and adds them to your own. This means that if you fill in the files yourself, the package will not remove anything from them.

Also, if you select a JSON file type, localization files will be automatically generated from existing translation files. JSON file will be sorted in alphabetical order.


### Install translations

When executing the `php artisan lang-translations:install` command, need to pass a list of localizations as a parameters:

```bash
php artisan lang-translations:install en
php artisan lang-translations:install en de ru
```

If files do not exist in the destination folder, they will be created. And if the files exist, the console will ask you for a replacement.

Also, if the files exist and you do not want to agree each time, you can pass the attribute --force or its alias -f for forced replacement.

```bash
php artisan lang-translations:install en --force
php artisan lang-translations:install en -f
```

By default, php translation files are copied. If you want to install json-files, then use the `--json` key:

```bash
php artisan lang-translations:install en --json
php artisan lang-translations:install en -j
```


### Update translations

When executing the `php artisan lang-translations:update` command, the package learns which localizations are installed in your application and will replace the matching files.

Command `php artisan lang-translations:update` is an alias of `php artisan lang-translations:install {langs} --force`.

And command for updating json files: `php artisan lang-translations:update --json`


## Status of files

Check the [TODO](TODO.md) file to see the missing translations.


## Copyright and License

`Lang Translations` was written by Andrey Helldar for the Laravel Framework 5.3 and later, and is released under the MIT License. See the [LICENSE](LICENSE) file for details.
