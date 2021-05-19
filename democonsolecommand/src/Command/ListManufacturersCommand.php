<?php

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
