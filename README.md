# CodeIgniter-for-PHP_CodeSniffer

Original Repos:
- https://github.com/thomas-ernest/CodeIgniter-for-PHP_CodeSniffer
- https://github.com/ganl/CodeIgniter-for-PHP_CodeSniffer

Usage:
```
composer require ise/php-codingstandards-codeigniter:^1.0.0
```

Provides sniffs for [PHP_CodeSniffer 3.x][PHP_CodeSniffer] to check [CodeIgniter3 coding standard][styleguide].

CI4 can use [CodeIgniter4 standard][CodeIgniter4-Standard] to check [CodeIgniter4 coding standard][CodeIgniter4-styleguide]

### PHP_CodeSniffer

[PHP_CodeSniffer][] is a set of two PHP scripts; the main phpcs script that tokenizes PHP, JavaScript and CSS files to detect violations of a defined coding standard, 
and a second phpcbf script to automatically correct coding standard violations. 

PHP_CodeSniffer is an essential development tool that ensures your code remains clean and consistent.

By default sniffs for a few coding convetions are provided like PEAR, Zend, PHPCS and Squiz.
**CodeIgniter-for-PHP_CodeSniffer** is aimed at adding support for CodeIgniter coding convention.

### CodeIgniter coding standard

[CodeIgniter][] is a powerful PHP framework with a very small footprint,
built for PHP coders who need a simple and elegant toolkit to create full-featured web applications.

CodeIgniter is developed by EllisLab. The company follows some specific
[coding rules][styleguide] for their developments and for CodeIgniter especially.

Based on PHP_CodeSniffer **CodeIgniter-for-PHP_CodeSniffer** helps to
validate most of the rules in CodeIgniter coding standard.

## Installation

`git clone https://github.com/ganl/CodeIgniter-for-PHP_CodeSniffer.git`

configure PHP_CodeSniffer to use CodeIgniter-for-PHP_CodeSniffer.

`phpcs --config-set installed_paths /path/to/CodeIgniter-for-PHP_CodeSniffer/CodeIgniter`

Check that it is installed type `phpcs -i` you should see a list of installed standards.

Then you can go to you project folder and run `phpcs --standard=CodeIgniter my-file-or-my-directory.php`.

## Enable integration in PhpStorm

![screenshot](https://github.com/ganl/mdAssets/raw/master/img/CodeIgniter3-Standard/code-standard.png)
![screenshot](https://github.com/ganl/mdAssets/raw/master/img/CodeIgniter3-Standard/codesniffer-effect.png)

[PHP_CodeSniffer]: https://github.com/squizlabs/PHP_CodeSniffer
[codesniffer-www]: http://www.squizlabs.com/php-codesniffer
[CodeIgniter]: https://codeigniter.com/
[styleguide]: https://codeigniter.com/userguide3/general/styleguide.html
[CodeIgniter4-styleguide]: https://codeigniter.com/userguide/general/styleguide.html
[CodeIgniter4-Standard]: https://github.com/bcit-ci/CodeIgniter4-Standard
