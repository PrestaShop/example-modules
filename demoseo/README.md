# Demo SEO

## About

This module illustrates how you can alter the SEO related data using the [actionFrontControllerSetVariables hook](https://devdocs.prestashop-project.org/9/modules/concepts/hooks/list-of-hooks/actionfrontcontrollersetvariables/) in a module.

It shows you how you can modify the structured data coming from the core added in [#37552](https://github.com/PrestaShop/PrestaShop/pull/37552), and also, how you can modify meta titles and descriptions for any page or object.

## Supported PrestaShop versions

`actionFrontControllerSetVariables` was added in PrestaShop 1.7.5.0, but the minimal version in this module is set to 9.2.0, because that's the version where the structured data was added.

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demoseo`*
3. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demoseo`

_* Because the name of the directory and the name of the main module file must match._

### How to test

- Visit any category page in the front office and see that the meta title looks like "Category name for the best prices | Shop"
- Visit any product page, check source code and see that structured data contain the additional data added by this module. You can also validate them using https://search.google.com/test/rich-results. Make sure your theme displays the server structured data - [example](https://github.com/PrestaShop/hummingbird/pull/1037).
