# example_module_mailtheme
Example module to add a Mail theme to PrestaShop.

# Requirements

This module requires PrestaShop 9.0.0 to work correctly.

# How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `example_module_mailtheme`*
3. `cd` into module's directory and run following commands:
   - `composer install` - to download dependencies into vendor folder
4. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install example_module_mailtheme`

_* Because the name of the directory and the name of the main module file must match._

# Usage

1. Once the module is installed you can go to Design > Email theme there is a new `dark_modern` theme available.
2. You can look at the `dark_modern` themes layout and see they look like the modern theme but with customized colors.
3. You can look at the `order_conf` layout and see that an additional block has been added at the bottom `Thank you for purchasing this product ...` on BOTH `modern` and `dark_modern` themes
4. You can see that all three themes have a `customized_template` layout with a customized message with the color red (applied via a custom transformation).
5. You can also go to Modules Manager and configure the module custom colors and text
6. Go back in the layout preview and check that the `dark_modern` template colors are adapted
