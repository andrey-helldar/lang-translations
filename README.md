# Lang Translations Library for Laravel 5.4+

Translation of main messages.

![Lang Translations Library](https://user-images.githubusercontent.com/10347617/39757295-1676df7c-52d5-11e8-90b1-88eb8511aa63.png)

<p align="center">
<a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://img.shields.io/packagist/dt/andrey-helldar/lang-translations.svg?style=flat-square" alt="Total Downloads" /></a>
<a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
<a href="https://packagist.org/packages/andrey-helldar/lang-translations"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
<a href="https://github.com/andrey-helldar/lang-translations"><img src="https://poser.pugx.org/andrey-helldar/lang-translations/license?format=flat-square" alt="License" /></a>
</p>


<p align="center">
<a href="https://www.versioneye.com/php/andrey-helldar:lang-translations/dev-master"><img src="https://www.versioneye.com/php/andrey-helldar:lang-translations/dev-master/badge?style=flat-square" alt="Dependency Status" /></a>
<a href="https://styleci.io/repos/132602203"><img src="https://styleci.io/repos/132602203/shield" alt="StyleCI" /></a>
<a href="https://php-eye.com/package/andrey-helldar/lang-translations"><img src="https://php-eye.com/badge/andrey-helldar/lang-translations/tested.svg?style=flat" alt="PHP-Eye" /></a>
</p>


## Installation

To get the latest version of Lang Translations Library, simply require the project using [Composer](https://getcomposer.org):

```
composer require andrey-helldar/lang-translations
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/lang-translations": "^1.0"
    }
}
```

If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
Helldar\LangTranslations\ServiceProvider::class,
```

You can also publish the config file to change implementations (ie. interface to specific class):

```
php artisan vendor:publish --provider="Helldar\LangTranslations\ServiceProvider"
```


## Using

### Important

The package replaces only certain files in your lang directories:

    buttons.php
    errors.php
    statuses.php

He does not touch any other files.


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
php artisan lang-translations:install en --f
```


### Update translations

When executing the `php artisan lang-translations:update` command, the package learns which localizations are installed in your application and will replace the matching files.

Command `php artisan lang-translations:update` is an alias of `php artisan lang-translations:install {langs} --force`.


## Copyright and License

`Lang Translations Library` was written by Andrey Helldar for the Laravel Framework 5.4 and later, and is released under the MIT License. See the [LICENSE](LICENSE) file for details.
