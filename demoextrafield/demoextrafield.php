<?php

/**
 * For the full copyright and license information, please view the
 * docs/licenses/LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

use PrestaShop\PrestaShop\Core\ExtraProperty\Definition\ExtraPropertyDefinition;
use PrestaShop\PrestaShop\Core\ExtraProperty\Definition\ExtraPropertyScope;
use PrestaShop\PrestaShop\Core\ExtraProperty\Definition\ExtraPropertySqlIndex;
use PrestaShop\PrestaShop\Core\ExtraProperty\Definition\ExtraPropertyType;
use PrestaShopBundle\Form\Admin\Sell\Discount\DiscountSupplierType;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Demo module showcasing native extra fields (custom fields).
 *
 * This module is intentionally simple and verbose:
 * - fields are registered one by one (no loops, no config arrays),
 * - a few hooks are used to render the values on the Front Office.
 */
class demoextrafield extends Module
{
    protected const TRANSLATION_DOMAIN = 'Modules.Demoextrafield.Admin';

    public function __construct()
    {
        $this->name = 'demoextrafield';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'PrestaShop';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = ['min' => '9.2.0', 'max' => '9.9.99'];

        parent::__construct();

        $this->displayName = 'Demo native extra fields';
        $this->description = 'Example module showing how to register and display native extra fields.';
    }

    /**
     * Install:
     * - registers extra fields for product/category/customer,
     * - registers a few FO hooks to display values.
     */
    public function install(): bool
    {
        if (!parent::install()) {
            return false;
        }

        /**
         * PRODUCT extra fields
         */

        // Product (common) : is_dangerous
        $productDangerousRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'product',
                propertyName: 'is_dangerous',
                type: ExtraPropertyType::BOOL,
                scope: ExtraPropertyScope::COMMON,
                defaultValue: 0,
                nullable: false,
                displayApi: true,
                associatedForms: ['product:options.suppliers:before'],
                associatedGrids: ['product:reference'],
                formFieldType: SwitchType::class,
                validator: 'isBool',
                labelWording: 'Dangerous product',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Indicates whether the product is dangerous',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$productDangerousRegistered) {
            $this->_errors[] = $this->trans('Failed to register Product extra field "is_dangerous" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Product (lang) : video_link
        $productVideoLinkRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'product',
                propertyName: 'video_link',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::LANG,
                nullable: true,
                sqlIndex: ExtraPropertySqlIndex::UNIQUE,
                displayApi: true,
                associatedForms: ['product'],
                formFieldType: UrlType::class,
                validator: 'isUrl',
                labelWording: 'Video link',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Video URL per language',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$productVideoLinkRegistered) {
            $this->_errors[] = $this->trans('Failed to register Product extra field "video_link" (scope: lang).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Product (shop) : custom_date
        $productCustomDateRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'product',
                propertyName: 'custom_date',
                type: ExtraPropertyType::DATE,
                scope: ExtraPropertyScope::SHOP,
                nullable: true,
                sqlIndex: ExtraPropertySqlIndex::KEY,
                displayApi: true,
                associatedForms: ['product'],
                associatedGrids: ['product:final_price_tax_excluded:before'],
                formFieldType: DatePickerType::class,
                validator: 'isDate',
                labelWording: 'Custom date',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Custom date per shop',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$productCustomDateRegistered) {
            $this->_errors[] = $this->trans('Failed to register Product extra field "custom_date" (scope: shop).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Product (common) : date_last_seen
        // Auto-updated on each FO product page view (hookDisplayFooterProduct).
        // displayForm: false → read-only for merchants; visible in the product grid and via API.
        $productDateLastSeenRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'product',
                propertyName: 'date_last_seen',
                type: ExtraPropertyType::DATE,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                associatedGrids: ['product'],
                labelWording: 'Date last seen',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Last time this product page was viewed',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$productDateLastSeenRegistered) {
            $this->_errors[] = $this->trans('Failed to register Product extra field "date_last_seen" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Product (common) : packaging_type
        // Demonstrates: CHOICE type, enumValues, formOptions (dropdown choices).
        // enumValues constrains the allowed DB values; formOptions drives the Symfony ChoiceType widget.
        // formRequired: false + nullable: true + placeholder → the "—" option represents "no selection";
        // the field can be left empty and the empty value passes server-side validation.
        // (For a truly required field, omit the placeholder and set formRequired: true — NotBlank is added automatically.)
        $productPackagingTypeRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'product',
                propertyName: 'packaging_type',
                type: ExtraPropertyType::CHOICE,
                scope: ExtraPropertyScope::COMMON,
                enumValues: ['standard', 'gift', 'bulk'],
                defaultValue: null,
                nullable: true,
                formRequired: false,
                displayApi: true,
                displayFront: true,
                associatedForms: ['product'],
                associatedGrids: ['product'],
                formFieldType: ChoiceType::class,
                formOptions: [
                    'choices' => [
                        'Standard' => 'standard',
                        'Gift box' => 'gift',
                        'Bulk' => 'bulk',
                    ],
                    'placeholder' => '—',
                ],
                labelWording: 'Packaging type',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Selectable packaging type for this product',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$productPackagingTypeRegistered) {
            $this->_errors[] = $this->trans('Failed to register Product extra field "packaging_type" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        /**
         * CATEGORY extra fields
         */

        // Category (common) : theme_color
        // Demonstrates: formRequired: true → the form modifier automatically adds a NotBlank
        // constraint at build time (server-side enforcement, not just the HTML required attribute).
        // No need to put constraints in formOptions — formOptions is persisted as JSON and cannot
        // hold Constraint objects.
        $categoryThemeColorRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'category',
                propertyName: 'theme_color',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                formRequired: true,
                displayApi: true,
                associatedForms: ['category', 'root_category'],
                associatedGrids: ['category'],
                formFieldType: ColorType::class,
                validator: 'isColor',
                labelWording: 'Theme color',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Color associated with the category (required)',
                descriptionDomain: self::TRANSLATION_DOMAIN
            )
        );
        if (!$categoryThemeColorRegistered) {
            $this->_errors[] = $this->trans('Failed to register Category extra field "theme_color" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Category (common) : marketing_note
        // Demonstrates: displayFront: false on an entity rendered through a presenter LazyArray
        // (CategoryLazyArray) — validates that the forFrontOffice filtering works on that path
        // too (the customer's internal_note covers the native ObjectModel path).
        $categoryMarketingNoteRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'category',
                propertyName: 'marketing_note',
                type: ExtraPropertyType::HTML,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                displayFront: false,
                associatedForms: ['category'],
                formFieldType: FormattedTextareaType::class,
                validator: 'isCleanHtml',
                labelWording: 'Marketing note',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Merchant-only note displayed in BO and API — never on the front office',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$categoryMarketingNoteRegistered) {
            $this->_errors[] = $this->trans('Failed to register Category extra field "marketing_note" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Category (common) : id_supplier
        $categorySupplierRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'category',
                propertyName: 'id_supplier',
                type: ExtraPropertyType::INT,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                associatedForms: ['category'],
                associatedGrids: ['category'],
                formFieldType: DiscountSupplierType::class,
                formOptions: [
                    'label_tag_name' => null,
                ],
                // This prevents using a h3 tag for label
                validator: 'isUnsignedId',
                labelWording: 'Default supplier',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Select a PrestaShop supplier',
                descriptionDomain: self::TRANSLATION_DOMAIN
            )
        );
        if (!$categorySupplierRegistered) {
            $this->_errors[] = $this->trans('Failed to register Category extra field "id_supplier" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        /**
         * CUSTOMER extra fields
         */

        // Customer (common) : credit_limit
        $customerCreditLimitRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'customer',
                propertyName: 'credit_limit',
                type: ExtraPropertyType::FLOAT,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                associatedForms: ['customer'],
                associatedGrids: ['customer'],
                formFieldType: MoneyType::class,
                validator: 'isPrice',
                labelWording: 'Credit limit',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Maximum customer credit amount',
                descriptionDomain: self::TRANSLATION_DOMAIN
            )
        );
        if (!$customerCreditLimitRegistered) {
            $this->_errors[] = $this->trans('Failed to register Customer extra field "credit_limit" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Customer (common) : extra_json
        $customerExtraJsonRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'customer',
                propertyName: 'extra_json',
                type: ExtraPropertyType::JSON,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                associatedForms: ['customer'],
                formFieldType: TextareaType::class,
                validator: 'isJson',
                labelWording: 'Metadata JSON',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Free JSON for customer metadata',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$customerExtraJsonRegistered) {
            $this->_errors[] = $this->trans('Failed to register Customer extra field "extra_json" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // Customer (common) : internal_note
        // Demonstrates: displayFront: false — the field appears in BO form and API but is
        // never readable on the front office: presenter lazy arrays are built with
        // forFrontOffice: true, and native ObjectModel bags detect the FO controller
        // context automatically, so non-displayFront definitions are never even read.
        $customerInternalNoteRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'customer',
                propertyName: 'internal_note',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                displayFront: false,
                associatedForms: ['customer'],
                formFieldType: TextareaType::class,
                labelWording: 'Internal note',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Merchant-only note — never exposed on the front office',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$customerInternalNoteRegistered) {
            $this->_errors[] = $this->trans('Failed to register Customer extra field "internal_note" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        /**
         * ADDRESS extra fields
         *
         * Demo case: gridId ('manufacturer_address') differs from entity name ('address').
         * This validates that getDefinitionCollectionByGridId() correctly decouples
         * the grid identifier from the entity table name.
         *
         * Note on displayForm:
         * The form modifier resolves extra fields using the form type's block prefix as the entity
         * name. ManufacturerAddressType has block_prefix='manufacturer_address', but the entity
         * table is 'address'. Because block_prefix ≠ entity_name, the form modifier cannot find
         * definitions registered for 'address' when building the 'manufacturer_address' form.
         * displayForm is therefore set to false: the field is intentionally grid-only here.
         * (Forms where block_prefix == entity_name — e.g. product, customer, category — work
         * correctly without this constraint.)
         */

        // Address (common) : delivery_note
        // Shows in the manufacturer address grid (Catalog > Brands > Addresses) after 'city'.
        $addressDeliveryNoteRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'address',
                propertyName: 'delivery_note',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                size: 255,
                displayApi: true,
                associatedGrids: ['manufacturer_address:city'],
                formFieldType: TextareaType::class,
                validator: 'isGenericName',
                labelWording: 'Delivery note',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Free delivery note attached to this address',
                // gridId 'manufacturer_address' ≠ entity 'address' — decoupling test.
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$addressDeliveryNoteRegistered) {
            $this->_errors[] = $this->trans('Failed to register Address extra field "delivery_note" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        /**
         * CMS extra fields — MANUAL form integration (no associatedForms)
         *
         * Demo case: the module integrates its fields into the migrated CMS page form
         * itself via the generic form hooks (actionCmsPageFormBuilderModifier,
         * actionCmsPageFormDataProviderData, actionAfterCreate/UpdateCmsPageFormHandler)
         * and persists them natively through the ObjectModel:
         *     $cms->extra_properties['demoextrafield']['promo_banner'] = [id_lang => value];
         *     $cms->update();
         * promo_banner is LANG-scoped to validate the native multilang round-trip
         * (no langId in the constructor → all languages read/modified/saved at once).
         */

        // CMS (lang) : promo_banner
        $cmsPromoBannerRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'cms',
                propertyName: 'promo_banner',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::LANG,
                nullable: true,
                displayApi: true,
                validator: 'isGenericName',
                labelWording: 'Promo banner',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Translated promotional text displayed on the CMS page',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$cmsPromoBannerRegistered) {
            $this->_errors[] = $this->trans('Failed to register CMS extra field "promo_banner" (scope: lang).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        // CMS (common) : revision_code
        $cmsRevisionCodeRegistered = $this->registerExtraProperty(
            new ExtraPropertyDefinition(
                entityName: 'cms',
                propertyName: 'revision_code',
                type: ExtraPropertyType::STRING,
                scope: ExtraPropertyScope::COMMON,
                nullable: true,
                displayApi: true,
                validator: 'isGenericName',
                labelWording: 'Revision code',
                labelDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Internal revision code displayed on the CMS page',
                descriptionDomain: self::TRANSLATION_DOMAIN,
            )
        );
        if (!$cmsRevisionCodeRegistered) {
            $this->_errors[] = $this->trans('Failed to register CMS extra field "revision_code" (scope: common).', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        $hooksRegistered = $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('displayCartExtraProductInfo')
            && $this->registerHook('displayHeaderCategory')
            && $this->registerHook('displayCustomerAccountTop')
            && $this->registerHook('displayFooterProduct')
            && $this->registerHook('actionCmsPageFormBuilderModifier')
            && $this->registerHook('actionCmsPageFormDataProviderData')
            && $this->registerHook('actionAfterCreateCmsPageFormHandler')
            && $this->registerHook('actionAfterUpdateCmsPageFormHandler')
            && $this->registerHook('displayCMSDisputeInformation');
        if (!$hooksRegistered) {
            $this->_errors[] = $this->trans('Failed to register one or more hooks.', [], 'Modules.Demoextrafield.Admin');

            return false;
        }

        return true;
    }

    /**
     * Uninstall:
     * - unregisters all extra fields,
     * - drops SQL storage columns,
     * - unregisters all hooks.
     */
    public function uninstall(): bool
    {
        // false = keep columns in DB after uninstall
        $dropColumn = false;

        return
            // Scope is not needed to identify a definition: (entity, module, property) is unique across scopes.
            $this->unregisterExtraProperty(new ExtraPropertyDefinition('product', 'video_link'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('product', 'is_dangerous'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('product', 'custom_date'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('product', 'date_last_seen'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('product', 'packaging_type'), $dropColumn)

            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('category', 'theme_color'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('category', 'marketing_note'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('category', 'id_supplier'), $dropColumn)

            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('customer', 'credit_limit'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('customer', 'extra_json'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('customer', 'internal_note'), $dropColumn)

            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('address', 'delivery_note'), $dropColumn)

            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('cms', 'promo_banner'), $dropColumn)
            && $this->unregisterExtraProperty(new ExtraPropertyDefinition('cms', 'revision_code'), $dropColumn)

            && $this->unregisterHook('displayProductAdditionalInfo')
            && $this->unregisterHook('displayCartExtraProductInfo')
            && $this->unregisterHook('displayHeaderCategory')
            && $this->unregisterHook('displayCustomerAccountTop')
            && $this->unregisterHook('displayFooterProduct')
            && $this->unregisterHook('actionCmsPageFormBuilderModifier')
            && $this->unregisterHook('actionCmsPageFormDataProviderData')
            && $this->unregisterHook('actionAfterCreateCmsPageFormHandler')
            && $this->unregisterHook('actionAfterUpdateCmsPageFormHandler')
            && $this->unregisterHook('displayCMSDisputeInformation')

            && parent::uninstall();
    }

    /**
     * Front Office hook (product page).
     * Displays this module extra fields from the product LazyArray.
     */
    public function hookDisplayProductAdditionalInfo(array $params): string
    {
        return $this->display(__FILE__, 'views/templates/hook/product_additional_info.tpl');
    }

    /**
     * Front Office hook (product page footer).
     *
     * Demo: reads date_last_seen from the Product ObjectModel, displays it, then updates it.
     *
     * Access is grouped by module: $product->extra_properties['module']['field']
     */
    public function hookDisplayFooterProduct(array $params): string
    {
        $productId = (int) ($params['product']['id_product'] ?? 0);
        if ($productId <= 0) {
            return '';
        }

        $product = new Product($productId);
        if (!Validate::isLoadedObject($product)) {
            return '';
        }

        $now = date('Y-m-d H:i:s');

        $dateLastSeen = $product->extra_properties['demoextrafield']['date_last_seen'];
        $product->extra_properties['demoextrafield']['date_last_seen'] = $now;
        $product->update();

        $this->context->smarty->assign([
            'dateLastSeen' => $dateLastSeen,
            'dateLastSeenUpdated' => $now,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/product_footer.tpl');
    }

    /**
     * Front Office hook (cart).
     * Displays this module extra fields for products in cart.
     *
     * $params['product'] is the product LazyArray passed by the cart template.
     */
    public function hookDisplayCartExtraProductInfo(array $params): string
    {
        $this->context->smarty->assign('product', $params['product'] ?? []);

        return $this->display(__FILE__, 'views/templates/hook/cart_extra_product_info.tpl');
    }

    /**
     * Front Office hook (category listing page).
     * Displays this module extra fields from the category LazyArray.
     */
    public function hookDisplayHeaderCategory(): string
    {
        return $this->display(__FILE__, 'views/templates/hook/category_header.tpl');
    }

    /**
     * Front Office hook (customer my-account page).
     *
     * Demonstrates that an ObjectModel instance can be handed to Smarty as-is: the template
     * reads the lazy ExtraPropertiesBag through object syntax
     * ({$customerObjectModel->extra_properties.demoextrafield.field_name}) — no presenter,
     * no array conversion. The first hop uses `->` (ObjectModel is not ArrayAccess); the bag
     * levels then support dot syntax and iteration.
     *
     * (The presented `$customer` Smarty global would work too — ObjectPresenter populates its
     * `extra_properties` key — but passing the ObjectModel directly is the point of this demo.)
     *
     * No manual filtering is needed: since this hook runs in a front-office controller, the
     * native bag is built with forFrontOffice: true automatically, so fields like
     * 'internal_note' (displayFront: false) are never read nor exposed.
     */
    public function hookDisplayCustomerAccountTop(): string
    {
        $customer = $this->context->customer;
        if (null === $customer || (int) $customer->id <= 0) {
            return '';
        }

        $this->context->smarty->assign('customerObjectModel', $customer);

        return $this->display(__FILE__, 'views/templates/hook/customer_account_top.tpl');
    }

    /**
     * Back Office hook (CMS page form, Design > Pages) — MANUAL form integration, step 1/3.
     *
     * Adds the two CMS extra fields to the migrated Symfony form. They are NOT registered
     * with associatedForms, so the native ExtraPropertiesFormBuilderModifier ignores them;
     * the module owns the whole integration (same pattern as the devdocs sample
     * "extending a Symfony form", but persistence goes through the ObjectModel natively —
     * no custom table, no Doctrine entity).
     *
     * TranslatableType submits [id_lang => value] — exactly the shape the ExtraPropertiesBag
     * uses for lang-scoped fields.
     */
    public function hookActionCmsPageFormBuilderModifier(array $params): void
    {
        $params['form_builder']
            ->add('demoextrafield_promo_banner', TranslatableType::class, [
                'type' => TextType::class,
                'label' => $this->trans('Promo banner (demoextrafield)', [], 'Modules.Demoextrafield.Admin'),
                'required' => false,
            ])
            ->add('demoextrafield_revision_code', TextType::class, [
                'label' => $this->trans('Revision code (demoextrafield)', [], 'Modules.Demoextrafield.Admin'),
                'required' => false,
            ]);
    }

    /**
     * Back Office hook (CMS page edit form) — MANUAL form integration, step 2/3 (prefill).
     *
     * $params['data'] is passed by reference BEFORE the form is created, so values set here
     * pre-populate the fields added in hookActionCmsPageFormBuilderModifier.
     *
     * The CMS ObjectModel is instantiated WITHOUT a langId: promo_banner comes back as a
     * [id_lang => value] array — the exact data shape TranslatableType expects.
     */
    public function hookActionCmsPageFormDataProviderData(array $params): void
    {
        $cmsId = (int) ($params['id'] ?? 0);
        if ($cmsId <= 0) {
            return;
        }

        $cms = new CMS($cmsId);
        $params['data']['demoextrafield_promo_banner'] = (array) ($cms->extra_properties['demoextrafield']['promo_banner'] ?? []);
        $params['data']['demoextrafield_revision_code'] = (string) ($cms->extra_properties['demoextrafield']['revision_code'] ?? '');
    }

    /**
     * Back Office hook (CMS page form submit, creation) — MANUAL form integration, step 3/3.
     */
    public function hookActionAfterCreateCmsPageFormHandler(array $params): void
    {
        $this->saveCmsExtraProperties($params);
    }

    /**
     * Back Office hook (CMS page form submit, update) — MANUAL form integration, step 3/3.
     */
    public function hookActionAfterUpdateCmsPageFormHandler(array $params): void
    {
        $this->saveCmsExtraProperties($params);
    }

    /**
     * Persists the two CMS extra fields natively through the ObjectModel.
     *
     * This is the native multilang round-trip: the CMS is instantiated WITHOUT a langId,
     * so assigning the full [id_lang => value] array to the lang-scoped field updates ALL
     * languages in one save — persistExtraProperties() validates each language and issues
     * one UPSERT per language into cms_extra_lang (plus one into cms_extra for the common field).
     */
    private function saveCmsExtraProperties(array $params): void
    {
        $cmsId = (int) ($params['id'] ?? 0);
        $formData = (array) ($params['form_data'] ?? []);
        if ($cmsId <= 0 || [] === $formData) {
            return;
        }

        $cms = new CMS($cmsId);
        if (!Validate::isLoadedObject($cms)) {
            return;
        }

        $cms->extra_properties['demoextrafield']['promo_banner'] = (array) ($formData['demoextrafield_promo_banner'] ?? []);
        $cms->extra_properties['demoextrafield']['revision_code'] = (string) ($formData['demoextrafield_revision_code'] ?? '');
        $cms->update();
    }

    /**
     * Front Office hook (CMS page, e.g. /content/4-about-us).
     *
     * The CMS ObjectModel is instantiated WITH the context langId: lang-scoped fields come
     * back as scalars for the current language, and the bag is FO-filtered automatically
     * (front-office controller context).
     */
    public function hookDisplayCMSDisputeInformation(array $params): string
    {
        $cmsId = (int) Tools::getValue('id_cms');
        if ($cmsId <= 0) {
            return '';
        }

        $this->context->smarty->assign('cmsObjectModel', new CMS($cmsId, (int) $this->context->language->id));

        return $this->display(__FILE__, 'views/templates/hook/cms_page_extra.tpl');
    }
}
