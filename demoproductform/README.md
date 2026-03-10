# Module demoproductform

## About

This is example module explaining various extendability options in product page form (from v9.0.0):
1. Add custom text field in product form basic tab
2. Save custom text field into database using dedicated object model
3. Show how to use ProductCommandsBuilder (specific to new Product page)
4. Use Module [modern translation system](https://devdocs.prestashop.com/8/modules/creation/module-translation/new-system/)

### Supported PrestaShop versions

Compatible with 9.0.0 and above versions.

### Requirements

1. Composer, see [Composer](https://getcomposer.org/) to learn more

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demoproductform`*
3. `cd` into module's directory and run following commands:
   - `composer install` - to download dependencies into vendor folder
4. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demoproductform`

_* Because the name of the directory and the name of the main module file must match._
