<?php
/**
 * 2007-2020 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0).
 * It is also available through the world-wide-web at this URL: https://opensource.org/licenses/AFL-3.0
 */

use DemoCQRSHooksUsage\Domain\Reviewer\Command\UpdateIsAllowedToReviewCommand;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotCreateReviewerException;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\CannotToggleAllowedToReviewStatusException;
use DemoCQRSHooksUsage\Domain\Reviewer\Exception\ReviewerException;
use DemoCQRSHooksUsage\Domain\Reviewer\Query\GetReviewerSettingsForForm;
use DemoCQRSHooksUsage\Domain\Reviewer\QueryResult\ReviewerSettingsForForm;
use Doctrine\DBAL\Query\QueryBuilder;
use PrestaShop\PrestaShop\Core\CommandBus\CommandBusInterface;
use PrestaShop\PrestaShop\Core\Domain\Customer\Exception\CustomerException;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use PrestaShop\PrestaShop\Core\Grid\Filter\Filter;
use PrestaShop\PrestaShop\Core\Search\Filters\CustomerFilters;
use PrestaShopBundle\Form\Admin\Type\SwitchType;
use PrestaShopBundle\Form\Admin\Type\YesAndNoChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DemoExtendSymfonyForm1 demonstrates the usage of CQRS pattern and hooks.
 */
class DemoExtendSymfonyForm4 extends Module
{
    public function __construct()
    {
        $this->name = 'demoextendsymfonyform4';
        $this->version = '1.0.0';
        $this->author = 'Mathieu Ferment';
        $this->need_instance = 0;

        parent::__construct();

        $this->displayName = $this->trans(
            'Demo Symfony Forms #4',
            [],
            'Modules.Democqrshooksusage.Admin'
        );

        $this->description =
            $this->trans(
                'Help developers to understand how to create module using CQRS',
                [],
                'Modules.Democqrshooksusage.Admin'
            );

        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => '9.99.99',
        ];
    }

    /**
     * This function is required in order to make module compatible with new translation system.
     *
     * @return bool
     */
    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    /**
     * Install module and register hooks to allow grid modification.
     *
     * @see https://devdocs.prestashop.com/1.7/modules/concepts/hooks/use-hooks-on-modern-pages/
     *
     * @return bool
     */
    public function install()
    {
        return parent::install() &&
            // Register hook to allow Customer grid definition modifications.
            // Each grid's definition modification hook has it's own name. Hook name is built using
            // this structure: "action{grid_id}GridDefinitionModifier", in this case "grid_id" is "customer"
            // this means we will be modifying "Sell > Customers" page grid.
            // You can check any definition factory service in PrestaShop\PrestaShop\Core\Grid\Definition\Factory
            // to see available grid ids. Grid id is returned by `getId()` method.
            $this->registerHook('actionCustomerGridDefinitionModifier') &&
            // Register hook to allow Customer grid query modifications which allows to add any sql condition.
            $this->registerHook('actionCustomerGridQueryBuilderModifier') &&
            // Register hook to allow overriding customer form
            // this structure: "action{block_prefix}FormBuilderModifier", in this case "block_prefix" is "customer"
            // {block_prefix} is either retrieved automatically by its type. E.g "ManufacturerType" will be "manufacturer"
            // or it can be modified in form type by overriding "getBlockPrefix" function
            $this->registerHook('actionCustomerFormBuilderModifier') &&
            $this->registerHook('actionAfterCreateCustomerFormHandler') &&
            $this->registerHook('actionAfterUpdateCustomerFormHandler') &&
            $this->installTables()
            ;
    }

    public function uninstall()
    {
        return parent::uninstall() && $this->uninstallTables();
    }

    /**
     * Hook allows to modify Customers grid definition.
     * This hook is a right place to add/remove columns or actions (bulk, grid).
     *
     * @param array $params
     */
    public function hookActionCustomerGridDefinitionModifier(array $params)
    {
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];

        $definition
            ->getColumns()
            ->addAfter(
                'optin',
                (new ToggleColumn('is_allowed_for_review'))
                    ->setName($this->trans('Allowed for review', [], 'Modules.Democqrshooksusage.Admin'))
                    ->setOptions([
                        'field' => 'is_allowed_for_review',
                        'primary_field' => 'id_customer',
                        'route' => 'ps_democqrshooksusage_toggle_is_allowed_for_review',
                        'route_param_name' => 'customerId',
                    ])
            )
        ;

        $definition->getFilters()->add(
            (new Filter('is_allowed_for_review', YesAndNoChoiceType::class))
                ->setAssociatedColumn('is_allowed_for_review')
        );
    }

    /**
     * Hook allows to modify Customers query builder and add custom sql statements.
     *
     * @param array $params
     */
    public function hookActionCustomerGridQueryBuilderModifier(array $params)
    {
        /** @var QueryBuilder $searchQueryBuilder */
        $searchQueryBuilder = $params['search_query_builder'];

        /** @var CustomerFilters $searchCriteria */
        $searchCriteria = $params['search_criteria'];

        $searchQueryBuilder->addSelect(
            'IF(dcur.`is_allowed_for_review` IS NULL,0,dcur.`is_allowed_for_review`) AS `is_allowed_for_review`'
        );

        $searchQueryBuilder->leftJoin(
            'c',
            '`' . pSQL(_DB_PREFIX_) . 'democqrshooksusage_reviewer`',
            'dcur',
            'dcur.`id_customer` = c.`id_customer`'
        );

        if ('is_allowed_for_review' === $searchCriteria->getOrderBy()) {
            $searchQueryBuilder->orderBy('dcur.`is_allowed_for_review`', $searchCriteria->getOrderWay());
        }

        foreach ($searchCriteria->getFilters() as $filterName => $filterValue) {
            if ('is_allowed_for_review' === $filterName) {
                $searchQueryBuilder->andWhere('dcur.`is_allowed_for_review` = :is_allowed_for_review');
                $searchQueryBuilder->setParameter('is_allowed_for_review', $filterValue);

                if (!$filterValue) {
                    $searchQueryBuilder->orWhere('dcur.`is_allowed_for_review` IS NULL');
                }
            }
        }
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     */
    public function hookActionCustomerFormBuilderModifier(array $params)
    {
        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $params['form_builder'];
        $formBuilder->add('is_allowed_for_review', SwitchType::class, [
            'label' => $this->trans('Allow reviews', [], 'Modules.Democqrshooksusage.Admin'),
            'required' => false,
        ]);

        /**
         * @var CommandBusInterface
         */
        $queryBus = $this->get('prestashop.core.query_bus');

        /**
         * This part demonstrates the usage of CQRS pattern query to perform read operation from Reviewer entity.
         *
         * @see https://devdocs.prestashop.com/1.7/development/architecture/cqrs/ for more detailed information.
         *
         * As this is our recommended approach of reading the data but we not force to use this pattern in modules -
         * you can use directly an entity here or wrap it in custom service class.
         *
         * @var ReviewerSettingsForForm
         */
        $reviewerSettings = $queryBus->handle(new GetReviewerSettingsForForm($params['id']));

        $params['data']['is_allowed_for_review'] = $reviewerSettings->isAllowedForReview();

        $formBuilder->setData($params['data']);
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     *
     * @throws CustomerException
     */
    public function hookActionAfterUpdateCustomerFormHandler(array $params)
    {
        $this->updateCustomerReviewStatus($params);
    }

    /**
     * Hook allows to modify Customers form and add additional form fields as well as modify or add new data to the forms.
     *
     * @param array $params
     *
     * @throws CustomerException
     */
    public function hookActionAfterCreateCustomerFormHandler(array $params)
    {
        $this->updateCustomerReviewStatus($params);
    }

    /**
     * @param array $params
     *
     * @throws \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException
     */
    private function updateCustomerReviewStatus(array $params)
    {
        $customerId = $params['id'];
        /** @var array $customerFormData */
        $customerFormData = $params['form_data'];
        $isAllowedForReview = (bool) $customerFormData['is_allowed_for_review'];

        /** @var CommandBusInterface $commandBus */
        $commandBus = $this->get('prestashop.core.command_bus');

        try {
            /*
             * This part demonstrates the usage of CQRS pattern command to perform write operation for Reviewer entity.
             * @see https://devdocs.prestashop.com/1.7/development/architecture/domain/cqrs/ for more detailed information.
             *
             * As this is our recommended approach of writing the data but we not force to use this pattern in modules -
             * you can use directly an entity here or wrap it in custom service class.
             */
            $commandBus->handle(new UpdateIsAllowedToReviewCommand(
                $customerId,
                $isAllowedForReview
            ));
        } catch (ReviewerException $exception) {
            $this->handleException($exception);
        }
    }

    /**
     * Installs sample tables required for demonstration.
     *
     * @return bool
     */
    private function installTables()
    {
        $sql = '
            CREATE TABLE IF NOT EXISTS `' . pSQL(_DB_PREFIX_) . 'democqrshooksusage_reviewer` (
                `id_reviewer` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                `id_customer` INT(10) UNSIGNED NOT NULL,
                `is_allowed_for_review` TINYINT(1) NOT NULL,
                PRIMARY KEY (`id_reviewer`)
            ) ENGINE=' . pSQL(_MYSQL_ENGINE_) . ' COLLATE=utf8_unicode_ci;
        ';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Uninstalls sample tables required for demonstration.
     *
     * @return bool
     */
    private function uninstallTables()
    {
        $sql = 'DROP TABLE IF EXISTS `' . pSQL(_DB_PREFIX_) . 'democqrshooksusage_reviewer`';

        return Db::getInstance()->execute($sql);
    }

    /**
     * Handles exceptions and displays message in more user friendly form.
     *
     * @param ReviewerException $exception
     *
     * @throws \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException
     */
    private function handleException(ReviewerException $exception)
    {
        $exceptionDictionary = [
            CannotCreateReviewerException::class => $this->trans(
                'Failed to create a record for customer',
                [],
                'Modules.Democqrshooksusage.Admin'
            ),
            CannotToggleAllowedToReviewStatusException::class => $this->trans(
                'Failed to toggle is allowed to review status',
                [],
                'Modules.Democqrshooksusage.Admin'
            ),
        ];

        $exceptionType = get_class($exception);

        if (isset($exceptionDictionary[$exceptionType])) {
            $message = $exceptionDictionary[$exceptionType];
        } else {
            $message = $this->trans(
                'An unexpected error occurred. [%type% code %code%]',
                [
                    '%type%' => $exceptionType,
                    '%code%' => $exception->getCode(),
                ],
                'Admin.Notifications.Error'
            );
        }

        throw new \PrestaShop\PrestaShop\Core\Module\Exception\ModuleErrorException($message);
    }
}
