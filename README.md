# Extended Lang Translations

Translation support for 78 languages.

![lang translations](https://user-images.githubusercontent.com/10347617/40197728-f289d00c-5a1c-11e8-877a-7ac379ceb4a2.png)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]


## Installation

To get the latest version of `Lang Translations` library, simply require the project using [Composer](https://getcomposer.org):

```
$ composer require andrey-helldar/lang-translations --dev
```

Instead, you may of course manually update your `require` block and run `composer update` if you so choose:

```json
{
    "require": {
        "andrey-helldar/lang-translations": "^4.0"
    }
}
```

## Using

> ATTENTION!
>
> Just specifying the namespace is not enough - the translation manager **DOES NOT INSTALL** additional packages - it uses the installed ones, so don't forget to [install](#installation) it.

Starting from version **4.0** this project will not contain installation code, only localization files.

To install files from this repository into your project, you need to install the [andrey-helldar/laravel-lang-publisher](https://github.com/andrey-helldar/laravel-lang-publisher)
and specify the namespace of this project in its configuration.

For example:

```php
// config/lang-publisher.php

<?php

return [
    // ...

    /*
     * Determines from which packages to synchronize localization files.
     *
     * A prerequisite is compliance with a single file placement format:
     *
     * source/
     * locales/
     *   af/
     *     af.json
     *     <filename>.php
     *   <locale>/
     *     <locale>.json
     *     <filename>.php
     */

    'packages' => [
        'andrey-helldar/lang-translations',
    ],
];
```

[badge_stable]:     https://img.shields.io/github/v/release/andrey-helldar/lang-translations?label=stable&style=flat-square

[badge_unstable]:   https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[badge_downloads]:  https://img.shields.io/packagist/dt/andrey-helldar/lang-translations.svg?style=flat-square

[badge_license]:    https://img.shields.io/packagist/l/andrey-helldar/lang-translations.svg?style=flat-square

[link_packagist]:   https://packagist.org/packages/andrey-helldar/lang-translations

[link_license]:     LICENSE
