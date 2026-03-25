# Demo extra fields

## About

This module demonstrates how to use **native extra fields** (custom fields) in PrestaShop (9.2+ ?).

It focuses on:

- Registering extra fields on multiple entities (Product, Category, Customer)
- Covering multiple **scopes** (`common`, `lang`, `shop`) and **types** (bool, date, money, html, json, url, …)
- Unregistering extra fields on uninstall (including dropping the SQL storage columns)
- Rendering the stored values on the Front Office using hooks
- Making Back Office translation strings visible in the translation interface

## What it registers

### Product (`product`)

- `is_dangerous` (scope: `common`, type: bool)
- `video_link` (scope: `lang`, type: string/url)
- `custom_date` (scope: `shop`, type: date)

### Category (`category`)

- `theme_color` (scope: `common`, type: string/color)
- `marketing_note` (scope: `common`, type: html)
- `id_supplier` (scope: `common`, type: int / supplier selector)

### Customer (`customer`)

- `credit_limit` (scope: `common`, type: float / money)
- `extra_json` (scope: `common`, type: json)

## How to test

This module impacts both Back Office and Front Office.

### Product

**Back Office grid**

- Adds a **"Dangerous product"** field displayed after **"Quantity"**.
- Adds a **"Custom date"** field displayed at the end of the grid.
- Toggling **"Dangerous product"** persists the value.

**Back Office form**

- Extra fields are grouped into a dedicated **"Extra fields"** tab.
- Except **"Dangerous product"**, which is displayed at the end of the **"Options"** tab.

**Front Office hooks**

- Product page: `displayProductAdditionalInfo`
- Cart: `displayCartExtraProductInfo`

### Category

**Back Office grid**

- Adds **Theme color** and **Marketing note** at the end of the grid.

**Back Office form**

- Adds **Theme color** and **Marketing note** to the form.

**Front Office hooks**

- Category listing page: `displayHeaderCategory`

### Customer

**Back Office grid**

- Adds **Credit limit** in the grid.

**Back Office form**

- Adds **Credit limit** and **Metadata JSON** to the form.

**Front Office hooks**

- My account page: `displayCustomerAccountTop`

### Where to find values in FO templates

On the Front Office, the module displays **only the values stored for this module**, under `extraProperties['demoextrafield']`.

## Translation note (Back Office)

Each extra field has a **title** and a **description** meant to be displayed in Back Office.
The system stores the source wording and its translation domain (for the default language), then translations are managed through PrestaShop Back Office.

To make those strings appear in the Back Office translation interface, two conditions must be met:

1. The strings must be declared in PHP via `$this->trans(...)` (see `demoextrafield::registerTranslationWordings()`).
2. The same source strings must exist at least once in an XLF file shipped by the module (see `translations/fr-FR/ModulesDemoextrafieldAdmin.fr-FR.xlf`).

## Supported PrestaShop versions

Compatible with 9.2 ? and above versions.

## How to install

1. Download or clone the module into the `modules` directory of your PrestaShop installation
2. Install the module:
  - from Back Office in Module Manager
  - or using the command `php ./bin/console prestashop:module install demoextrafield`

