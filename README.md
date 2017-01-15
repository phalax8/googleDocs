# googleDocs
Class to integrate and work with google docs.

## Requirements ##
* [PHP 5.4.0 or higher](http://www.php.net/).
* [Google api php client](http://developers.google.com/api-client-library/php).

## Installation ##

You can use **Composer** or simply **Download the Release**

### Composer

The preferred method is via [composer](https://getcomposer.org). Follow the
[installation instructions](https://getcomposer.org/doc/00-intro.md) if you do not already have
composer installed.

Once composer is installed, execute the following command in your project root to install this library:

```sh
composer require google/apiclient:^2.0
```

Finally, be sure to include the autoloader:

```php
require_once '/path/to/your-project/vendor/autoload.php';
```

### Download the Release

If you abhor using composer, you can download the package in its entirety. The [Releases](https://github.com/google/google-api-php-client/releases) page lists all stable versions. Download any file
with the name `google-api-php-client-[RELEASE_NAME].zip` for a package including this library and its dependencies.

Uncompress the zip file you download, and include the autoloader in your project:

```php
require_once '/path/to/google-api-php-client/vendor/autoload.php';
```

For additional installation and setup instructions, see [the documentation](https://developers.google.com/api-client-library/php/start/installation).

## Examples ##
See the [`test/`](test) directory for examples of the features.
