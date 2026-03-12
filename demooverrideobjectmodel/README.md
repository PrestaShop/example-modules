# Module DemoOverrideObjectModel

## About

Example module showing how to override an ObjectModel (in this case the manufacturer) and add a custom field in the database table.

### Supported PrestaShop versions

Tested on 9.0.0, but same principle applies to all versions.

### Requirements

1. Composer, see [Composer](https://getcomposer.org/) to learn more

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demooverrideobjectmodel`*
3. `cd` into module's directory and run following commands:
   - `composer install` - to download dependencies into vendor folder
4. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demooverrideobjectmodel`

_* Because the name of the directory and the name of the main module file must match._
