# FileNamingResolver

A lightweight library to resolve files or directories naming using various strategies.

> This library solves only naming things and do *nothing* with any files or
  directories directly. If you are looking for some filesystem abstraction
  layer - looks closer to [Gaufrette][5], [symfony/filesystem][6] or [Flysystem][7]
  libraries.

[![Build Status](https://travis-ci.org/bocharsky-bw/FileNamingResolver.svg?branch=master)](https://travis-ci.org/bocharsky-bw/FileNamingResolver)

## Contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [Strategy list](#strategy-list)
    * [AggregateNamingStrategy](#aggregatenamingstrategy)
    * [CallbackNamingStrategy](#callbacknamingstrategy)
    * [DatetimeNamingStrategy](#datetimenamingstrategy)
    * [HashNamingStrategy](#hashnamingstrategy)
* [Contribution](#contribution)

## Requirements

This library is standalone without any dependencies. To use it in your project
ensure you correspond to the next requirements:

* PHP `5.3` or higher

## Installation

The preferred way to install this package is to use [Composer][3]:

```bash
$ composer require bocharsky-bw/file-naming-resolver
```

If you don't use `Composer` - register this package in your autoloader manually
following [PSR-4][4] autoloader standard or simply download this library and
`require` the necessary files directly in your scripts:

```php
require_once __DIR__ . '/path/to/library/src/FileNamingResolver.php';
// and other files you intend to use...
```

## Usage

First of all, before using file naming resolver, you should determine which naming
strategy to use. You can use different strategies out-of-the-box or easily create
your own one by implementing the `NamingStrategyInterface` interface or extends the
`AbstractNamingStrategy` class which already implemented it for you.

```php
// don't forget to use namespaces
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

// Create source file info object from full filename
$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create at least one naming strategy object
$hashStrategy = new HashNamingStrategy();

// Create file naming resolver and pass naming strategy to it
$resolver = new FileNamingResolver($hashStrategy);

// Resolve new name using specified naming strategy
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/4e/d3/a51a07c8e89ff8f228075b7fc76b.jpg
```

> **NOTE:** In all examples hereinafter the `__DIR__` equals to `/var/www/html/web`.

## Strategy list

### AggregateNamingStrategy

This naming strategy allows to use as many naming strategies as you need at once.
Its aggregate results. Each new result filename based on the previous one.

```php
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\AggregateNamingStrategy;
use FileNamingResolver\NamingStrategy\DatetimeNamingStrategy;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

$datetimeStrategy = new DatetimeNamingStrategy();
$hashStrategy = new HashNamingStrategy();
// Create an aggregate naming strategy object
$strategies = [
    $datetimeStrategy,
    $hashStrategy,
    // and so on as many as you need...
];
$aggregateStrategy = new AggregateNamingStrategy($strategies);

$resolver = new FileNamingResolver($aggregateStrategy);
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/2015/12/9c/98/87cbf44f53c9f6fa08f44ce705c8.jpg
```

To reverse applying order of strategies pass `true` as second parameter to the
constructor of `AggregateNamingStrategy` class:

```php
$aggregateStrategy = new AggregateNamingStrategy($strategies, AggregateNamingStrategy::MODE_REVERSE);

$resolver = new FileNamingResolver($aggregateStrategy);
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/a0/cb/2015/12/11-23-35-039900.jpg
```

### CallbackNamingStrategy

This naming strategy allows create a custom naming logic using custom callbacks.

```php
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\CallbackNamingStrategy;

$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create a custom callback naming strategy object
$callbackStrategy = new CallbackNamingStrategy(function (FileInfo $srcFileInfo) {
    $dstFileInfo = $srcFileInfo
        // Add 'products' suffix to the path
        ->changePath($srcFileInfo->getPath().FileInfo::SEPARATOR_DIRECTORY.'products')
        // Generate custom basename
        ->changeBasename(time().'-'.uniqid()) // comment this line to keep original basename
        // or do whatever custom naming logic you want here...
    ;

    return $dstFileInfo->toString();
});

$resolver = new FileNamingResolver($callbackStrategy);
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/products/1450004778-566d512a32d2c.jpg
```

### DatetimeNamingStrategy

The naming behavior of datetime naming strategy looks like [WordPress][9] naming
of uploaded media files.

```php
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\DatetimeNamingStrategy;

$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create a datetime naming strategy object
$datetimeStrategy = new DatetimeNamingStrategy(
    // DateTime format for directories, by default: 'Y/m'
    DateTimeNamingStrategy::FORMAT_DIR_YEAR_MONTH_DAY,         // 'Y/m/d'
    // DateTime format for files, by default: 'H-i-s-u'
    DateTimeNamingStrategy::FORMAT_FILE_TIMESTAMP_MICROSECONDS // 'U-u'
);

$resolver = new FileNamingResolver($datetimeStrategy);
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/2015/12/13/1450004392-907500.jpg
```

### HashNamingStrategy

The naming behavior of hash naming strategy looks like [Twig][8] naming of cached files.

```php
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create a hash naming strategy object
$hashStrategy = new HashNamingStrategy(
    // Hashing algorithm, by default: 'md5'
    HashNamingStrategy::ALGORITHM_SHA1, // 'sha1'
    // Count of parts for explode, by default: 2
    3,
    // Length of each exploded part, by default: 2
    3
);

$resolver = new FileNamingResolver($hashStrategy);
$filename = $resolver->resolveName($srcFileInfo);
echo $filename; // /var/www/html/web/uploads/4ed/3a5/1a0/7c8e89ff8f228075b7fc76b.jpg
```

## Contribution

Feel free to submit an [Issue][1] or create a [Pull Request][2] if you find
a bug or just want to propose an improvement suggestion.

In order to propose a new feature, the best way is to submit an [Issue][1]
and discuss it first.

[Move UP](#filenamingresolver)


[1]: https://github.com/bocharsky-bw/FileNamingResolver/issues
[2]: https://github.com/bocharsky-bw/FileNamingResolver/pulls
[3]: https://getcomposer.org/
[4]: http://www.php-fig.org/psr/psr-4/
[5]: https://github.com/KnpLabs/Gaufrette
[6]: https://github.com/symfony/filesystem
[7]: https://github.com/thephpleague/flysystem
[8]: https://github.com/twigphp/Twig
[9]: https://github.com/wordpress/wordpress