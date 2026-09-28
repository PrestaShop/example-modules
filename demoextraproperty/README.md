# Demo extra fields

## About

This module demonstrates how to use **native extra fields** (custom fields) in PrestaShop (9.2+ ?).

It focuses on:

- Registering extra fields on multiple entities (Product, Category, Customer, Address, CMS, Cart, Order, Combination)
- Covering multiple **scopes** (`common`, `lang`, `shop`) and **types** (bool, date, money, html, json, url, choice, …)
- Unregistering extra fields on uninstall (including dropping the SQL storage columns)
- Rendering the stored values on the Front Office using hooks
- Making Back Office translation strings visible in the translation interface

## What it registers

### Product (`product`)

- `is_dangerous` (scope: `common`, type: bool)
- `video_link` (scope: `lang`, type: string/url)
- `custom_date` (scope: `shop`, type: date)
- `date_last_seen` (scope: `common`, type: date — written by a FO hook, no form)
- `packaging_type` (scope: `common`, type: choice)

### Category (`category`)

- `theme_color` (scope: `common`, type: string/color)
- `marketing_note` (scope: `common`, type: html)
- `id_supplier` (scope: `common`, type: int / supplier selector)

### Customer (`customer`)

- `credit_limit` (scope: `common`, type: float / money)
- `extra_json` (scope: `common`, type: json) — the JSON showcase: the module **writes a PHP
  structure** (the core encodes it for storage) and **reads back the decoded structure**
  (iterated in the my-account template)
- `internal_note` (scope: `common`, type: string, `displayFront: false` — never reaches the FO)

### Address (`address`)

- `delivery_note` (scope: `common`, type: string — grid placement on the manufacturer addresses grid)

### CMS (`cms`) — manual form integration (no `associatedForms`)

- `promo_banner` (scope: `lang`, type: string)
- `revision_code` (scope: `common`, type: string)

### Cart (`cart`) — a COMMON-only entity

- `delivery_note` (scope: `common`, type: string) — seeded by `actionCartSave`, displayed on the
  checkout summary (`displayCheckoutSummaryTop`), and **copied onto the order** at validation
  (`actionValidateOrder`): the cart dies at the end of checkout, so cart values that must
  survive the purchase have to be copied to the order by the module.
- The cart has no `cart_lang` / `cart_shop` base table (its `id_lang`/`id_shop` are plain
  columns), so `lang` and `shop` scopes are rejected at registration for this entity.

### Order (`order`)

- `delivery_note` (scope: `common`, type: string) — filled from the cart at order validation,
  displayed on the customer's order detail page (`displayOrderDetail`). Registered with the
  natural entity name `order`: the core resolves the physical table (`orders`) and primary
  key (`id_order`) from the ObjectModel. No order-grid placement on purpose: the order grid
  uses id-first pagination, which the generic grid integration cannot join yet (core issue
  [#42536](https://github.com/PrestaShop/PrestaShop/issues/42536)).

### Combination (`combination`)

- `ean_verified` (scope: `common`, type: bool) — exposed on the Admin API combinations list
  (`/products/{productId}/combinations`)
- `restock_note` (scope: `shop`, type: string) — one value per store

Registered with the natural entity name `combination` (the `Combination`,
`product_attribute` and `ProductAttribute` spellings work identically): the core resolves
the physical table (`product_attribute`) and primary key (`id_product_attribute`).

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

### Cart & Order

- Add any product to the cart: `actionCartSave` seeds the cart `delivery_note` (once).
- Open the cart / checkout: the note is displayed above the cart summary
  (`displayCheckoutSummaryTop`, read from `{$cart.extra_properties.demoextrafield.delivery_note}`).
- Place the order: `actionValidateOrder` copies the note onto the order.
- In the customer account, open the order detail page: the copied note is displayed
  (`displayOrderDetail`).

### Combination

- Edit a product's combinations: `ean_verified` / `restock_note` are stored per combination
  (`restock_note` per store).
- Admin API: `GET /products/{productId}/combinations` returns `extra_demoextrafield_ean_verified`
  inline on each item.

### Where to find values in FO templates

On the Front Office, the module displays **only the values stored for this module**, under the
`extra_properties['demoextrafield']` key (snake_case — Smarty/presenter surfaces always use
`extra_properties`; the camelCase `extraProperties` spelling exists only in the Admin API JSON).
JSON-typed fields come back as **decoded structures** (arrays), not raw JSON strings.

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

