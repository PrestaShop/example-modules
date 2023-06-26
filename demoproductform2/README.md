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
2. Switch on the new product page feature flag in the backoffice configuration UI

#### How to install:
1. Copy the module into `modules` directory of your PrestaShop installation
2. `cd` into the module's directory and run `composer install --no-dev` (the --no-dev is important) to download dependencies into vendor directory
3. Install the module from Back Office
