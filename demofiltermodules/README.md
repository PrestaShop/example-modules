# Demo filter modules

## About

This module demonstrates how to use a new feature introduced in PrestaShop 8.2.1 to handle filtering of the modules in the front office.

In this example module, we demonstrate how to filter the list of modules executed for a given hook based on the module's name. Specifically, the module filters `ps_linklist` from the `displayFooter` hook, and both `ps_contactinfo` and `ps_customeraccountlinks` from the `displayFooter` hook when the visited page is the contact page.

### Supported PrestaShop versions

PrestaShop 8.2.1+

## Requirements

 1. Composer, see [Composer](https://getcomposer.org/) to learn more

## How to install

 1. Download or clone module into `modules` directory of your PrestaShop installation
 2. Rename the directory to make sure that module directory is named `demofiltermodules`*
 3. `cd` into module's directory and run following commands:
     - `composer install` - to download dependencies into vendor folder
 4. Install module:
  - from Back Office in Module Catalog
  - using the command `php ./bin/console prestashop:module install demofiltermodules`
