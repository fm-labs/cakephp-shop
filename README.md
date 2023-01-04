# Shop plugin for CakePHP

An ecommerce plugin for CupCake - An opinionated extension of the CakePHP framework.
 
**This plugin is still under development - Use at your own risk ;)**


## Requirements

CakePHP v4

## Dependencies

* fm-labs/cakephp-cupcake - Opinionated toolset for Cakephp
* fm-labs/cakephp-admin - Opinionated admin toolset for Cakephp 

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require fm-labs/cakephp-shop
```

- Enable in your Application.php

```php
class Application extends \Cupcake\Application {
    public function bootstrap() : void{
        parent::bootstrap();
        
        // load shop plugin
        $this->addPlugin('Shop')
    }
}
```


- Copy default config to application's config directory
        

    $ cp ./plugins/Shop/config/shop.php to ./config/plugins/shop.php
    
    # Edit configuration settings, if necessary


## Features

* Shop Products Management
* Minimal Ordering System
* Payment processing
  * Klarna
  * MPAY24
* Customer discounts
* Email notifications
* PDF generator for order confirmations and invoices
* Coupons
* ...

