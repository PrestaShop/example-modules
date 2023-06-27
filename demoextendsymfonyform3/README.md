# Demonstration of how to use CQRS in a module

## About

This module adds a new field to Customer: a yes/no field "is allowed to review".
This new field appears:
- in the Customers listing as a new column
- in the Customers Add/Edit form as a new field you can manage

Allowed to review in List  | Allowed to review in Form
------------- | -------------
![Allowed to review in List](democqrshooksusage_list.png)  | ![Allowed to review in Form](democqrshooksusage_form.png)

This modules demonstrates
 - how to add this field, manage its content and its
properties using modern hooks in Symfony pages
 - how to use custom [CQRS](https://devdocs.prestashop.com/1.7/development/architecture/domain/cqrs/) Commands and Queries to separate your domain from your application
 - how to use Translator inside modern Symfony module

*This part is demonstrated as a possibility for your module, this is not mandatory to be done this way.

 ### Supported PrestaShop versions

PrestaShop 1.7.6 to PrestaShop 8.1.
 
 ### Requirements
 
  1. Composer, see [Composer](https://getcomposer.org/) to learn more
 
 ### How to install
 
  1. Download or clone module into `modules` directory of your PrestaShop installation
  2. Rename the directory to make sure that module directory is named `demoextendsymfonyform3`*
  3. `cd` into module's directory and run following commands:
      - `composer install` - to download dependencies into vendor folder
  4. Install module from Back Office
 
 _*Because the name of the directory and the name of the main module file must match._
 

