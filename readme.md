# Ikcms

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require piclou/ikcms
```

## Usage

- composer require "piclou/ikcms @dev"
- php artisan make:auth
- php artisan migrate
- php artisan ikcms:install
- php artisan vendor:publish --provider="Piclou\Ikcms\IkcmsServiceProvider"
- php artisan vendor:publish --provider="Aschmelyun\Larametrics\LarametricsServiceProvider"
- Providers

Piclou\Ikcms\IkcmsServiceProvider::class,

'IkCms' => \Piclou\Ikcms\Facades\Ikcms::class,

'IkForm' => \Piclou\Ikcms\Facades\IkForm::class,

## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email vincent@ikon-k.Fr instead of using the issue tracker.

## Credits

- [Vincent Herbaut][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/piclou/ikcms.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/piclou/ikcms.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/piclou/ikcms/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/piclou/ikcms
[link-downloads]: https://packagist.org/packages/piclou/ikcms
[link-travis]: https://travis-ci.org/piclou/ikcms
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/piclou
[link-contributors]: ../../contributors]
