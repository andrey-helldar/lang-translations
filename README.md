# Extended Lang Translations

Translation support for 78 languages.

![lang translations](https://user-images.githubusercontent.com/10347617/40197728-f289d00c-5a1c-11e8-877a-7ac379ceb4a2.png)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]

> Note
> 
> This package is abandoned. Use the [`laravel-lang/http-statuses`](https://github.com/Laravel-Lang/http-statuses) instead.


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

## License

This package is licensed under the [MIT License](LICENSE).


## For Enterprise

Available as part of the Tidelift Subscription.

The maintainers of `andrey-helldar/lang-translations` and thousands of other packages are working with Tidelift to deliver commercial support and maintenance for the open source packages you use to build your applications. Save time, reduce risk, and improve code health, while paying the maintainers of the exact packages you use. [Learn more](https://tidelift.com/subscription/pkg/packagist-andrey-helldar-lang-translations?utm_source=packagist-andrey-helldar-lang-translations&utm_medium=referral&utm_campaign=enterprise&utm_term=repo).



[badge_stable]:     https://img.shields.io/github/v/release/andrey-helldar/lang-translations?label=stable&style=flat-square

[badge_unstable]:   https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[badge_downloads]:  https://img.shields.io/packagist/dt/andrey-helldar/lang-translations.svg?style=flat-square

[badge_license]:    https://img.shields.io/packagist/l/andrey-helldar/lang-translations.svg?style=flat-square

[link_packagist]:   https://packagist.org/packages/andrey-helldar/lang-translations

[link_license]:     LICENSE
