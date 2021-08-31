<p align="center">
    <a href="https://sylius.com" target="_blank">
        <img src="https://demo.sylius.com/assets/shop/img/logo.png" />
    </a>
</p>

<h1 align="center">WebSummerCamp Workshop 2021</h1>

About
-----

Welcome to the Developing headless e-commerce with Sylius and API Platform workshop!

This repository contains a pre-installed Sylius-Standard installation and will be used as 

Documentation
-------------

Documentation is available at [docs.sylius.com](http://docs.sylius.com).

Prerequisite
------------

`WebSummerCamp VM` 

or

| Requirement | Version |
|-------------|---------|
| PHP         | 7.4/8.0 |
| Composer    | 2.0     |
| Yarn        | 1.22    |
| Node        | 14.x    |
| MySQL       | 5.7/8.0 | 
 
Installation
------------

`WebSummerCamp VM` has everything preinstalled. Check the website by accessing homepage:

```bash
symfony serve
open http://127.0.0.1:8000/
```

If you would like to work locally:

```bash
composer install -n
yarn install
yarn build
composer dump-env dev
# Set database credentials in .env.local.php
bin/console sylius:install -n
bin/console lexik:jwt:generate-keypair
symfony serve
open http://127.0.0.1:8000/
```
