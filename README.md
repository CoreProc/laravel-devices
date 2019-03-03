# Store and handle devices connecting to your web application

[![Latest Version on Packagist](https://img.shields.io/packagist/v/coreproc/laravel-devices.svg?style=flat-square)](https://packagist.org/packages/coreproc/laravel-devices)
[![Quality Score](https://img.shields.io/scrutinizer/g/coreproc/laravel-devices.svg?style=flat-square)](https://scrutinizer-ci.com/g/coreproc/laravel-devices)
[![Total Downloads](https://img.shields.io/packagist/dt/coreproc/laravel-devices.svg?style=flat-square)](https://packagist.org/packages/coreproc/laravel-devices)

This package sets up the database and middleware needed in storing devices. This is perfect for handling mobile devices
using your web API. You'll be able to identify each device, assign them an FCM token, and relate them to users as well.

## Installation

You can install the package via composer:

```bash
composer require coreproc/laravel-devices
```

You must publish the migration with:

```bash
php artisan vendor:publish --provider="Coreproc\Devices\DevicesServiceProvider" --tag="migrations"
```

Migrate the statuses table:

```bash
php artisan migrate
```

Optionally you can publish the config file with:

```bash
php artisan vendor:publish --provider="Coreproc\Devices\DevicesServiceProvider" --tag="config"
```

## Usage

To begin storing device information, you can attach the `store.device` middleware to any of your routes. Here is an
example:

```php
// routes/api.php

Route::middleware('store.device')->get('/test', function (Request $request) {
    return [];
});
```

Now, when you use the API endpoint `/api/test`, you can attach the device information to the header. Here is a complete
list of data that you can enter:

```bash
curl --request GET \
  --url http://devices.test/api/test \
  --header 'x-device-app-version: 1.0.1' \
  --header 'x-device-fcm-token: firebase-cloud-messaging-token' \
  --header 'x-device-manufacturer: Samsung' \
  --header 'x-device-model: Galaxy S10' \
  --header 'x-device-os: Android' \
  --header 'x-device-os-version: 8.0' \
  --header 'x-device-udid: unique-device-udid'
```

This will store all of the above information to the database. Only the `x-device-udid` header field is required.

If a user is authenticated, it will relate the user to this device automatically.

You can define the guard to be used in the first parameter of the middleware.

```php
// routes/api.php

Route::middleware('store.device:web')->get('/test', function (Request $request) {
    return [];
});
```

You can define if the device should be required or not in the second parameter, delimted by a comma.

```php
// routes/api.php

Route::middleware('store.device:web,0')->get('/test', function (Request $request) {
    return [];
});
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email chris.bautista@coreproc.ph instead of using the issue tracker.

## About CoreProc

CoreProc is a software development company that provides software development services to startups, digital/ad agencies, and enterprises.

Learn more about us on our [website](https://coreproc.com).

## Credits

- [Chris Bautista](https://github.com/chrisbjr)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
