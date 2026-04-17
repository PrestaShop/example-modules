<?php
/**
 * For the full copyright and license information, please view the
 * docs/licenses/LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

use PrestaShop\PrestaShop\Adapter\ContainerFinder;
use PrestaShop\PrestaShop\Core\ExtraProperty\ExtraPropertyOptions;
use PrestaShop\PrestaShop\Core\ExtraProperty\ExtraPropertyScope;
use PrestaShop\PrestaShop\Core\ExtraProperty\ExtraPropertySqlIndex;
use PrestaShop\PrestaShop\Core\ExtraProperty\ExtraPropertyType;
use PrestaShop\PrestaShop\Core\ExtraProperty\Storage\ExtraPropertyValueProviderInterface;
use PrestaShopBundle\Form\Admin\Sell\Discount\DiscountSupplierType;
use PrestaShopBundle\Form\Admin\Type\DatePickerType;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Demo module showcasing native extra fields (custom fields).
 *
 * This module is intentionally simple and verbose:
 * - fields are registered one by one (no loops, no config arrays),
 * - a few hooks are used to render the values on the Front Office,
 * - translation strings are declared in PHP + provided in an XLF file.
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
        $this->ps_versions_compliancy = ['min' => '9.1.0', 'max' => '9.9.99'];

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
            $errors = array_filter($this->getErrors());
            $details = empty($errors) ? 'no legacy errors were provided' : implode(' | ', $errors);

            throw new Exception(sprintf('demoextrafield: parent::install() failed (%s).', $details));
        }

        // IMPORTANT NOTE ON TRANSLATION:
        // Each extra field has a title + description meant to be displayed in Back Office.
        // We store the source wording + its translation domain (for the default language), then
        // PrestaShop handles translations for active languages via the BO translation system.
        //
        // For those strings to show up in the BO translation interface, two conditions must be met:
        // - the strings must be declared in PHP via $this->trans(...),
        // - the same source strings must exist at least once in an XLF file shipped by the module.
        $this->registerTranslationWordings();

        /**
         * PRODUCT extra fields
         */

        // Product (common) : is_dangerous
        $productDangerousRegistered = $this->registerExtraProperty(
            'product',
            'is_dangerous',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Bool,
                scope: ExtraPropertyScope::Common,
                nullable: false,
                defaultValue: 0,
                titleWording: 'Dangerous product',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Indicates whether the product is dangerous',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: CheckboxType::class,
                validator: 'isBool',
                displayApi: true,
                displayForm: true,
                formPosition: 'options.extra_properties',
                displayGrid: true,
                gridPosition: 'quantity'
            )
        );
        if (!$productDangerousRegistered) {
            $this->_errors[] = 'Failed to register Product extra field "is_dangerous" (scope: common).';

            return false;
        }

        // Product (lang) : video_link
        $productVideoLinkRegistered = $this->registerExtraProperty(
            'product',
            'video_link',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::String,
                scope: ExtraPropertyScope::Lang,
                nullable: true,
                titleWording: 'Video link',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Video URL per language',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                sqlIndex: ExtraPropertySqlIndex::Unique,
                formFieldType: UrlType::class,
                validator: 'isUrl',
                displayApi: true,
                displayForm: true,
                displayGrid: false
            )
        );
        if (!$productVideoLinkRegistered) {
            $this->_errors[] = 'Failed to register Product extra field "video_link" (scope: lang).';

            return false;
        }

        // Product (shop) : custom_date
        $productCustomDateRegistered = $this->registerExtraProperty(
            'product',
            'custom_date',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Date,
                scope: ExtraPropertyScope::Shop,
                nullable: true,
                titleWording: 'Custom date',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Custom date per shop',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                sqlIndex: ExtraPropertySqlIndex::Key,
                formFieldType: DatePickerType::class,
                validator: 'isDate',
                displayApi: true,
                displayForm: true,
                displayGrid: true,
                gridPosition: 3
            )
        );
        if (!$productCustomDateRegistered) {
            $this->_errors[] = 'Failed to register Product extra field "custom_date" (scope: shop).';

            return false;
        }

        /**
         * CATEGORY extra fields
         */

        // Category (common) : theme_color
        $categoryThemeColorRegistered = $this->registerExtraProperty(
            'category',
            'theme_color',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::String,
                scope: ExtraPropertyScope::Common,
                nullable: true,
                titleWording: 'Theme color',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Color associated with the category',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: ColorType::class,
                validator: 'isColor',
                displayApi: true,
                displayForm: true,
                displayGrid: true
            )
        );
        if (!$categoryThemeColorRegistered) {
            $this->_errors[] = 'Failed to register Category extra field "theme_color" (scope: common).';

            return false;
        }

        // Category (common) : marketing_note
        $categoryMarketingNoteRegistered = $this->registerExtraProperty(
            'category',
            'marketing_note',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Html,
                scope: ExtraPropertyScope::Common,
                nullable: true,
                titleWording: 'Marketing note',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Free note displayed in BO, API and FO',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: FormattedTextareaType::class,
                validator: 'isCleanHtml',
                displayApi: true,
                displayForm: true,
                displayGrid: false
            )
        );
        if (!$categoryMarketingNoteRegistered) {
            $this->_errors[] = 'Failed to register Category extra field "marketing_note" (scope: common).';

            return false;
        }

        // Category (common) : id_supplier
        $categorySupplierRegistered = $this->registerExtraProperty(
            'category',
            'id_supplier',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Int,
                scope: ExtraPropertyScope::Common,
                nullable: true,
                titleWording: 'Default supplier',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Select a PrestaShop supplier',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: DiscountSupplierType::class,
                validator: 'isUnsignedId',
                displayApi: true,
                displayForm: true,
                displayGrid: true
            )
        );
        if (!$categorySupplierRegistered) {
            $this->_errors[] = 'Failed to register Category extra field "id_supplier" (scope: common).';

            return false;
        }

        /**
         * CUSTOMER extra fields
         */

        // Customer (common) : credit_limit
        $customerCreditLimitRegistered = $this->registerExtraProperty(
            'customer',
            'credit_limit',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Float,
                scope: ExtraPropertyScope::Common,
                nullable: true,
                titleWording: 'Credit limit',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Maximum customer credit amount',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: MoneyType::class,
                validator: 'isPrice',
                displayApi: true,
                displayForm: true,
                displayGrid: true
            )
        );
        if (!$customerCreditLimitRegistered) {
            $this->_errors[] = 'Failed to register Customer extra field "credit_limit" (scope: common).';

            return false;
        }

        // Customer (common) : extra_json
        $customerExtraJsonRegistered = $this->registerExtraProperty(
            'customer',
            'extra_json',
            new ExtraPropertyOptions(
                type: ExtraPropertyType::Json,
                scope: ExtraPropertyScope::Common,
                nullable: true,
                titleWording: 'Metadata JSON',
                titleDomain: self::TRANSLATION_DOMAIN,
                descriptionWording: 'Free JSON for customer metadata',
                descriptionDomain: self::TRANSLATION_DOMAIN,
                formFieldType: TextareaType::class,
                validator: 'isJson',
                displayApi: true,
                displayForm: true,
                displayGrid: false
            )
        );
        if (!$customerExtraJsonRegistered) {
            $this->_errors[] = 'Failed to register Customer extra field "extra_json" (scope: common).';

            return false;
        }

        $hooksRegistered = $this->registerHook('displayProductAdditionalInfo')
            && $this->registerHook('displayCartExtraProductInfo')
            && $this->registerHook('displayHeaderCategory')
            && $this->registerHook('displayCustomerAccountTop');
        if (!$hooksRegistered) {
            $this->_errors[] = 'Failed to register one or more hooks (displayProductAdditionalInfo, displayCartExtraProductInfo, displayHeaderCategory, displayCustomerAccountTop).';

            return false;
        }

        return true;
    }

    /**
     * Uninstall:
     * - unregisters all extra fields,
     * - drops SQL storage columns.
     */
    public function uninstall(): bool
    {
        // false = keep columns in DB after uninstall
        $dropColumn = true;

        $this->unregisterExtraProperty('product', 'video_link', ExtraPropertyScope::Lang, $dropColumn);
        $this->unregisterExtraProperty('product', 'is_dangerous', ExtraPropertyScope::Common, $dropColumn);
        $this->unregisterExtraProperty('product', 'custom_date', ExtraPropertyScope::Shop, $dropColumn);

        $this->unregisterExtraProperty('category', 'theme_color', ExtraPropertyScope::Common, $dropColumn);
        $this->unregisterExtraProperty('category', 'marketing_note', ExtraPropertyScope::Common, $dropColumn);
        $this->unregisterExtraProperty('category', 'id_supplier', ExtraPropertyScope::Common, $dropColumn);

        $this->unregisterExtraProperty('customer', 'credit_limit', ExtraPropertyScope::Common, $dropColumn);
        $this->unregisterExtraProperty('customer', 'extra_json', ExtraPropertyScope::Common, $dropColumn);

        return parent::uninstall();
    }

    /**
     * Front Office hook (product page).
     * Displays this module extra fields from the product LazyArray.
     */
    public function hookDisplayProductAdditionalInfo(array $params): string
    {
        $product = $params['product'] ?? null;
        if (!$product instanceof ArrayAccess || (int) ($product['id_product'] ?? 0) <= 0) {
            return '';
        }

        $moduleExtras = $product['extraProperties'][$this->name] ?? [];
        if (!is_array($moduleExtras) && !$moduleExtras instanceof ArrayAccess) {
            $moduleExtras = [];
        }

        $this->context->smarty->assign([
            'demoExtraFieldTitle' => $this->trans('Extra fields (demoextrafield)', [], self::TRANSLATION_DOMAIN),
            'entityLabel' => $this->trans('Entity', [], self::TRANSLATION_DOMAIN),
            'entityName' => 'product',
            'moduleExtras' => $moduleExtras,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/product_additional_info.tpl');
    }

    /**
     * Front Office hook (cart).
     * Displays this module extra fields for products in cart.
     */
    public function hookDisplayCartExtraProductInfo(array $params): string
    {
        $product = $params['product'] ?? null;
        if (!$product instanceof ArrayAccess || (int) ($product['id_product'] ?? 0) <= 0) {
            return '';
        }

        $moduleExtras = $product['extraProperties'][$this->name] ?? [];
        if (!is_array($moduleExtras) && !$moduleExtras instanceof ArrayAccess) {
            $moduleExtras = [];
        }

        $this->context->smarty->assign([
            'demoExtraFieldTitle' => $this->trans('Extra fields (demoextrafield)', [], self::TRANSLATION_DOMAIN),
            'entityLabel' => $this->trans('Entity', [], self::TRANSLATION_DOMAIN),
            'entityName' => 'product',
            'moduleExtras' => $moduleExtras,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/cart_extra_product_info.tpl');
    }

    /**
     * Front Office hook (category listing page).
     * Displays this module extra fields from the category LazyArray.
     */
    public function hookDisplayHeaderCategory(): string
    {
        $category = $this->context->smarty->getTemplateVars('category');
        if (!$category instanceof ArrayAccess || (int) ($category['id_category'] ?? 0) <= 0) {
            return '';
        }

        $moduleExtras = $category['extraProperties'][$this->name] ?? [];
        if (!is_array($moduleExtras) && !$moduleExtras instanceof ArrayAccess) {
            $moduleExtras = [];
        }

        $this->context->smarty->assign([
            'demoExtraFieldTitle' => $this->trans('Extra fields (demoextrafield)', [], self::TRANSLATION_DOMAIN),
            'entityLabel' => $this->trans('Entity', [], self::TRANSLATION_DOMAIN),
            'entityName' => 'category',
            'moduleExtras' => $moduleExtras,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/category_header.tpl');
    }

    /**
     * Front Office hook (customer my-account page).
     * Displays this module extra fields for current customer.
     */
    public function hookDisplayCustomerAccountTop(): string
    {
        $customer = $this->context->customer;
        if (!$customer instanceof Customer || (int) $customer->id <= 0) {
            return '';
        }

        try {
            $containerFinder = new ContainerFinder($this->context);
            /** @var ExtraPropertyValueProviderInterface $extraPropertyValueProvider */
            $extraPropertyValueProvider = $containerFinder->getContainer()->get(ExtraPropertyValueProviderInterface::class);
        } catch (Throwable $e) {
            return '';
        }

        $extraProperties = $extraPropertyValueProvider->getExtraProperties(
            'customer',
            'id_customer',
            (int) $customer->id,
            (int) $this->context->language->id,
            (int) $this->context->shop->id,
            true,
            true
        );

        $moduleExtras = $extraProperties[$this->name] ?? [];
        if (!is_array($moduleExtras) && !$moduleExtras instanceof ArrayAccess) {
            $moduleExtras = [];
        }

        $this->context->smarty->assign([
            'demoExtraFieldTitle' => $this->trans('Extra fields (demoextrafield)', [], self::TRANSLATION_DOMAIN),
            'entityLabel' => $this->trans('Entity', [], self::TRANSLATION_DOMAIN),
            'entityName' => 'customer',
            'moduleExtras' => $moduleExtras,
        ]);

        return $this->display(__FILE__, 'views/templates/hook/customer_account_top.tpl');
    }

    /**
     * Declares translation wordings so BO extraction can index them.
     */
    protected function registerTranslationWordings(): void
    {
        $domain = self::TRANSLATION_DOMAIN;

        // Product
        $this->trans('Dangerous product', [], $domain);
        $this->trans('Indicates whether the product is dangerous', [], $domain);
        $this->trans('Video link', [], $domain);
        $this->trans('Video URL per language', [], $domain);
        $this->trans('Custom date', [], $domain);
        $this->trans('Custom date per shop', [], $domain);

        // Category
        $this->trans('Theme color', [], $domain);
        $this->trans('Color associated with the category', [], $domain);
        $this->trans('Marketing note', [], $domain);
        $this->trans('Free note displayed in BO, API and FO', [], $domain);
        $this->trans('Default supplier', [], $domain);
        $this->trans('Select a PrestaShop supplier', [], $domain);

        // Customer
        $this->trans('Credit limit', [], $domain);
        $this->trans('Maximum customer credit amount', [], $domain);
        $this->trans('Metadata JSON', [], $domain);
        $this->trans('Free JSON for customer metadata', [], $domain);

        // Front templates
        $this->trans('Extra fields (demoextrafield)', [], $domain);
        $this->trans('Entity', [], $domain);
        $this->trans('No extra fields found for this module.', [], $domain);
    }
}

