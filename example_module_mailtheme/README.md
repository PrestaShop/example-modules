# example_module_mailtheme
Example module to add a Mail theme to PrestaShop.

# Requirements

This module requires PrestaShop 9.0.0 to work correctly.

1. Copy the `example_module_mailtheme` directory into PrestaShop's `modules` directory.
2. `composer install` in the module's directory. (`[...]/modules/example_module_mailtheme`)
3. `bin/console prestashop:module install example_module_mailtheme` in PrestaShop's directory.

# Install

1. Copy the `example_module_mailtheme` directory into PrestaShop's `modules` directory.
2. `composer install` in the module's directory. (`[...]/modules/example_module_mailtheme`)
3. `bin/console prestashop:module install example_module_mailtheme` in PrestaShop's directory.

# Usage

1. Once the module is installed you can go to Design > Email theme there is a new `dark_modern` theme available.
2. You can look at the `dark_modern` themes layout and see they look like the modern theme but with customized colors.
3. You can look at the `order_conf` layout and see that an additional block has been added at the bottom `Thank you for purchasing this product ...` on BOTH `modern` and `dark_modern` themes
4. You can see that all three themes have a `customized_template` layout with a customized message with the color red (applied via a custom transformation).
5. You can also go to Modules Manager and configure the module custom colors and text
6. Go back in the layout preview and check that the `dark_modern` template colors are adapted
