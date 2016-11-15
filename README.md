# FileNamingResolver

A lightweight library which helps to resolve a file/directory naming of uploaded files
using various naming strategies.

> This library solves only naming things and do *nothing* with any files or
  directories directly. If you are looking for some filesystem abstraction
  layer - looks closer to [Gaufrette][5], [symfony/filesystem][6] or [Flysystem][7]
  libraries.

[![Travis Status](https://img.shields.io/travis/bocharsky-bw/FileNamingResolver/master.svg?style=flat-square)](https://travis-ci.org/bocharsky-bw/FileNamingResolver)
[![HHVM Status](https://img.shields.io/hhvm/bocharsky-bw/file-naming-resolver/master.svg?style=flat-square)](http://hhvm.h4cc.de/package/bocharsky-bw/file-naming-resolver)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/bocharsky-bw/FileNamingResolver.svg?style=flat-square)](https://scrutinizer-ci.com/g/bocharsky-bw/FileNamingResolver/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/bocharsky-bw/FileNamingResolver.svg?style=flat-square)](https://scrutinizer-ci.com/g/bocharsky-bw/FileNamingResolver/?branch=master)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](https://github.com/bocharsky-bw/FileNamingResolver/blob/master/LICENSE)
[![Latest Version](https://img.shields.io/packagist/v/bocharsky-bw/file-naming-resolver.svg?style=flat-square)](https://packagist.org/packages/bocharsky-bw/file-naming-resolver)

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/5a4cfa0f-22b1-4d76-adb3-373017f21ee8/big.png)](https://insight.sensiolabs.com/projects/5a4cfa0f-22b1-4d76-adb3-373017f21ee8)

## Contents

* [Requirements](#requirements)
* [Installation](#installation)
* [Usage](#usage)
* [FileInfo](#fileinfo)
* [Strategy list](#strategy-list)
    * [AggregateNamingStrategy](#aggregatenamingstrategy)
    * [CallbackNamingStrategy](#callbacknamingstrategy)
    * [ContentHashNamingStrategy](#contenthashnamingstrategy)
    * [DatetimeNamingStrategy](#datetimenamingstrategy)
    * [HashNamingStrategy](#hashnamingstrategy)
* [Example](#example)
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

// First of all, upload new file or use any uploaded file on the your server

// Create source FileInfo object from FQFN of uploaded file
$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create at least one naming strategy object
$hashStrategy = new HashNamingStrategy();

// Create file naming resolver and pass naming strategy to it
$namingResolver = new FileNamingResolver($hashStrategy);

// Resolve new name using specified naming strategy
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/4e/d3/a51a07c8e89ff8f228075b7fc76b.jpg

// Use rename() / move_uploaded_file() built-in functions, Gaufrette or any other filesystem
// abstraction library to move uploaded file to the directory according to suggested pathname.
```

> **NOTE:** In all examples hereinafter the `__DIR__` equals to `/var/www/html/web`.

## FileInfo

The `FileNamingResolver\FileInfo` class extends `\SplFileInfo` object. For more info
check the [SplFileInfo][10]. Below shown list of frequently used getters of this class:

```php
$fileInfo = new FileInfo('/var/www/html/web/uploads/products/image.jpg');

echo $fileInfo->toString();     // '/var/www/html/web/uploads/products/image.jpg'
echo $fileInfo->getPathname();  // '/var/www/html/web/uploads/products/image.jpg'
echo $fileInfo->getPath();      // '/var/www/html/web/uploads/products'
echo $fileInfo->getFilename();  // 'image.jpg'
echo $fileInfo->getBasename();  // 'image'
echo $fileInfo->getExtension(); // 'jpg'
echo $fileInfo->getPathRelativeTo('/var/www/html/web');     // 'uploads/products'
echo $fileInfo->getPathnameRelativeTo('/var/www/html/web'); // 'uploads/products/image.jpg'
```

## Strategy list

### AggregateNamingStrategy

This naming strategy allows to use as many naming strategies as you need at once.
Its aggregate results. Each new result pathname based on the previous one.

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

$namingResolver = new FileNamingResolver($aggregateStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/2015/12/9c/98/87cbf44f53c9f6fa08f44ce705c8.jpg
```

To reverse applying order of strategies pass `true` as second parameter to the
constructor of `AggregateNamingStrategy` class:

```php
$aggregateStrategy = new AggregateNamingStrategy($strategies, AggregateNamingStrategy::MODE_REVERSE);

$namingResolver = new FileNamingResolver($aggregateStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/a0/cb/2015/12/11-23-35-039900.jpg
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
        // Generate custom basename of the file without extension
        ->changeBasename(time().'-'.uniqid()) // comment this line to keep original basename
        // or do whatever custom naming logic you want here...
    ;

    return $dstFileInfo;
});

$namingResolver = new FileNamingResolver($callbackStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/products/1450004778-566d512a32d2c.jpg
```

### ContentHashNamingStrategy

The naming behavior of hash naming strategy looks like [Twig][8] naming of cached files.

```php
use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\ContentHashNamingStrategy;

$srcFileInfo = new FileInfo(__DIR__.'/uploads/image.jpg');

// Create a hash naming strategy object
$contentHashStrategy = new ContentHashNamingStrategy(
    // Hashing algorithm, by default: 'md5'
    ContentHashNamingStrategy::ALGORITHM_SHA1, // 'sha1'
    // Count of parts for explode, by default: 2
    3,
    // Length of each exploded part, by default: 2
    3,
    // Keep full filename for easy searching
    true
);

$namingResolver = new FileNamingResolver($contentHashStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/4ed/3a5/1a0/4ed3a51a07c8e89ff8f228075b7fc76b.jpg
```

> **NOTE:** Be sure that source file *really* exist before using `ContentHashNamingStrategy`
  otherwise an `InvalidArgumentException` will be thrown. You probably need to use a `isFile()`
  method on `FileInfo` object to be sure that file exists. It's necessary because this naming
  strategy try to hash a *real* file content.

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

$namingResolver = new FileNamingResolver($datetimeStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/2015/12/13/1450004392-907500.jpg
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
    3,
    // Keep full filename for easy searching
    true
);

$namingResolver = new FileNamingResolver($hashStrategy);
$dstFileInfo = $namingResolver->resolve($srcFileInfo);
echo $dstFileInfo->toString(); // /var/www/html/web/uploads/4ed/3a5/1a0/4ed3a51a07c8e89ff8f228075b7fc76b.jpg
```

## Example

There is a full working example how to upload files with a simple HTML form and
built-in PHP functions using FileNamingResolver library.

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use FileNamingResolver\FileInfo;
use FileNamingResolver\FileNamingResolver;
use FileNamingResolver\NamingStrategy\HashNamingStrategy;

// Define a few constants for convenience
define('WEB_ROOT_DIR', __DIR__);
define('UPLOAD_ROOT_DIR', WEB_ROOT_DIR.'/uploads');

// Check whether form was submitted...
if (isset($_POST['upload'])) {
    // and file was attached - process file uploading
    if ($_FILES['attachment']['tmp_name']) {
        $strategy = new HashNamingStrategy(); // or create any other strategy you need here
        $namingResolver = new FileNamingResolver($strategy); // use created naming strategy in resolver

        $dstFileInfo = new FileInfo(UPLOAD_ROOT_DIR.'/'.$_FILES['attachment']['name']); // build destination pathname
        $dstFileInfo = $namingResolver->resolve($dstFileInfo); // apply specified naming strategy to the destination file

        // Change extension based on mime type of uploaded file
        // WARNING: Could be skipped if you want to base on original extension in $_FILES['attachment']['name'] but it could cause some security issues!
        switch ($_FILES['attachment']['type']) {
            case 'image/jpeg':
                $extension = 'jpg';
                break;

            case 'image/png':
                $extension = 'png';
                break;

            default:
                $extension = 'bin';

        }
        // The FileInfo object is immutable so changeExtension() creates a new object.
        // Don't forget to override previous variable
        $dstFileInfo = $dstFileInfo->changeExtension($extension);

        // create non-existent directories for the destination file
        if (false === is_dir($dstFileInfo->getPath())) {
            if (false === mkdir($dstFileInfo->getPath(), 0777, true)) {
                throw new RuntimeException('Unable to create a directory for the destination file.');
            }
        }
        // move uploaded file according to the destination pathname
        if (false === move_uploaded_file($_FILES['attachment']['tmp_name'], $dstFileInfo->toString())) {
            throw new RuntimeException('Unable to move an uploaded file to the specified directory.');
        }

        // Do whatever you want after successful uploading (i.e redirect user after success uploading)
        echo sprintf(
            'File "<b>%s</b>" successfully uploaded to the "<b>%s</b>" web directory! :)',
            $dstFileInfo->getFilename(),
            $dstFileInfo->getPathRelativeTo(WEB_ROOT_DIR) // get path relative to the web directory
        );
    }
}

?>

<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="attachment">
    <input type="submit" name="upload" value="Upload file">
</form>
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
[10]: http://php.net/manual/en/class.splfileinfo.php
