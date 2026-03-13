# Demo Product Extra Content

## About

This module demonstrates how to add extra content tabs to the product page in the front office using the `displayProductExtraContent` hook.

The hook expects the implementation to return an array of `ProductExtraContent` objects, each with a title and content. By default, the theme renders them as additional tabs on the product page. This module adds two such tabs:

- **First custom tab** — with a static text content
- **Second custom tab** — with a static text content

### Supported PrestaShop versions

PrestaShop 1.7.0 and later.

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demoproductextracontent`*
3. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demoproductextracontent`

_* Because the name of the directory and the name of the main module file must match._

### How to test

1. Open any product page in the front office.
2. Two additional tabs — **First custom tab** and **Second custom tab** — should appear alongside the default product description tabs.
