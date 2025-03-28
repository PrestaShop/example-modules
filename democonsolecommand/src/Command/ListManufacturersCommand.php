<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License version 3.0
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

declare(strict_types=1);

namespace PrestaShop\Module\DemoConsoleCommand\Command;

use Manufacturer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListManufacturersCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('demo:list-manufacturers')
            ->setDescription('Lists existing manufacturer names')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manufacturers = Manufacturer::getManufacturers();

        if (!$manufacturers) {
            $output->writeln('<comment>There are no manufacturers</comment>');

            return 0;
        }

        $rows = [];
        foreach ($manufacturers as $manufacturer) {
            $rows[] = [
                $manufacturer['id_manufacturer'],
                $manufacturer['name'],
            ];
        }

        $table = new Table($output);

        $table->setHeaders(['id', 'name'])
            ->setRows($rows)
            ->render()
        ;

        return 0;
    }
}
