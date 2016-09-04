# Shop plugin for CakePHP

A ecommerce plugin for CakePHP application based on the BananaCake framework extension 

## Requirements

CakePHP v3.1+
BananaCake v1.4+

## Dependencies

fm-labs/cakephp3-banana
fm-labs/cakephp3-backend

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp3-shop
```

- Enable in your ROOT/config/bootstrap.php

    Plugin::load('Shop', ['bootstrap' => true, 'routes' => true]);


- Copy default config from plugins/Shop/config/backend.default.php to ROOT/config/backend.php
    Edit configuration settings, if necessary


## Features

* Shop Categories
* Shop Products
* Shop Catalogue
* Minimal Order System
* to be developed ...

