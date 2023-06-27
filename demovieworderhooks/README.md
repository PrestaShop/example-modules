## Demo View Order Hooks

## About

This module was created in order to demonstrate how to use the new hooks introduced with the new "View an Order" back office page in PrestaShop 8.0.0 and below.

It uses the following hooks:
- displayAdminOrderTabContent
- displayAdminOrderTabLink
- displayAdminOrderMain
- displayAdminOrderSide
- displayAdminOrderSideBottom
- displayAdminOrder
- displayAdminOrderTop
- displayOrderPreview
- actionGetAdminOrderButtons

Please note this module is an example only, not a mandatory structure.

## Requirements

 1. Composer, see [Composer](https://getcomposer.org/) to learn more

## How to install

 1. Download or clone module into `modules` directory of your PrestaShop installation
 2. Rename the directory to make sure that module directory is named `demovieworderhooks`*
 3. `cd` into module's directory and run following commands:
     - `composer install` - to download dependencies into vendor folder
 4. Install module from Back Office

*Because the name of the directory and the name of the main module file must match.*

## Hooks usage

### displayAdminOrderSide

We use this hook to display scanned customer signature.

### displayAdminOrderTabLink

We use this hook to display a "Package tracking" tab.
The content of this tab is provided in displayAdminOrderTabContent hook.

### displayAdminOrderTabContent

We use this hook to display an additional listing in the page.
The listing displays package tracking informations.
It is controlled and linked to displayAdminOrderTabLink tab.

### displayAdminOrderMain

We use this hook to display an additional listing in the page.
The listing displays customer _delivered_ orders.

### displayAdminOrderSideBottom

We use this hook to display a widget showing customer's review.

### displayAdminOrder

We use this hook to display an additional listing in the page.
The listing displays other orders from the same customer.

### displayAdminOrderTop

We use this hook to display blue previous/next order buttons in the top to ease the navigation.
Please note Order page already contains these buttons, but we put some additional buttons even easier to spot.

### displayOrderPreview

We use this hook on orders list, it's available in quick preview of the order after clicking a little blue arrow next to order ID.

### actionGetAdminOrderButtons

We use this hook to display additional action buttons into the main buttons bar.

## Other examples

In this module, we also demonstrated other usecases that you might find useful for building a module. However they are not mandatory to use in your module.

### Using Install Factory to handle install/uninstall steps

We put the install/uninstall logic of this module into a dedicated class for a better concerns separation, following [SOLID](https://en.wikipedia.org/wiki/SOLID) principles/

### Using Doctrine ORM to handle persisted models

We used [Doctrine ORM](https://github.com/doctrine/orm) to manage the persistence of multiple models, instead of PrestaShop ObjectModel ORM.
