# Module DemoOverrideObjectModel

## About

Example module showing how to override an ObjectModel (in this case the manufacturer) and add a custom field in the database table.

> **Warning:** Overrides are **not recommended** for production modules. Overriding a core class means only one module can override it at a time — any other module attempting the same override will fail to install. Extending core database tables carries additional risk: schema changes in future PrestaShop versions can break your override silently, and the approach is incompatible with multi-module ecosystems.
>
> **Prefer these alternatives instead:**
> - **Doctrine entities with a dedicated table** — store extra data in your own table and join it at the application level. See the [`demoextendsymfonyform2`](../demoextendsymfonyform2) module for an example.
> - **Form data provider hooks** (`action[FormName]FormDataProviderData` / `actionProductFormDataProviderData`) — read and write extra fields without touching core tables. See the [`demoformdataproviders`](../demoformdataproviders) module for an example.
> - **Identifiable object hooks** (`actionAfterCreate/Update/Delete[ObjectName]FormHandler`) — react to core object saves and persist your own data alongside them. See the [`demoextendsymfonyform1`](../demoextendsymfonyform1) module for an example.
>
> This module exists purely as a reference for understanding how the override mechanism works.

### Supported PrestaShop versions

Tested on 9.0.0, but same principle applies to all versions.

### Requirements

1. Composer, see [Composer](https://getcomposer.org/) to learn more

## How to install

1. Download or clone module into `modules` directory of your PrestaShop installation
2. Rename the directory to make sure that module directory is named `demooverrideobjectmodel`*
3. `cd` into module's directory and run following commands:
   - `composer install` - to download dependencies into vendor folder
4. Install module:
   - from Back Office in Module Manager
   - using the command `php ./bin/console prestashop:module install demooverrideobjectmodel`

_* Because the name of the directory and the name of the main module file must match._
