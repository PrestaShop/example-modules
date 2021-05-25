## Demo Multistore Form

## About

This module demonstrates how to make your forms multistore compatible in your module, it includes:

- A CRUD implementation with a shop association mechanism (grid list, edit/create form, deletion link)
- A multistore compatible configuration form (with multistore checkboxes and dropdowns)

 ### Supported PrestaShop versions

 This module is compatible with and 1.7.8.0 and above versions.

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
