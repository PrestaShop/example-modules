## Demo Multistore Form

## About

This module demonstrates how to make your forms multistore compatible in your module, in a CRUD context, it includes:

- A listing of elements using Prestashop's [grid component](https://devdocs.prestashop.com/1.7/development/components/grid/)
- A create/edit form, using [doctrine](https://devdocs.prestashop.com/1.7/modules/concepts/doctrine/) and a shop association block, via Prestashop's [ShopChoiceTreeType](https://devdocs.prestashop.com/1.7/development/components/form/types-reference/shop-choice-tree/)
- A multistore compatible form with multistore checkboxes and dropdowns, using Prestashop's [multistore form extension](https://devdocs.prestashop.com/1.7/development/multistore/configuration-forms/)
- A fixtures generation mechanism, using [doctrine](https://devdocs.prestashop.com/1.7/modules/concepts/doctrine/)
- Displaying the configured blocks on Front Office

 ### Supported PrestaShop versions

 This module is compatible with and 1.7.8.0 only. Next version compatibility is expected for future versions.

 ### Requirements

  1. Composer, see [Composer](https://getcomposer.org/) to learn more

 ### How to install

  1. Download or clone module into `modules` directory of your PrestaShop installation
  2. Rename the directory to make sure that module directory is named `demomultistoreform`*
  3. `cd` into module's directory and run following commands:
      - `composer install` - to download dependencies into vendor folder
  4. Install module from Back Office
  5. In the BO, activate multistore in Shop parameters > Preferences
  6. Create and configure at least one second shop

 *Because the name of the directory and the name of the main module file must match.
