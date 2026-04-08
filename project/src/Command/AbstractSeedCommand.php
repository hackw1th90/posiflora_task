<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class AbstractSeedCommand
 *
 * @package App\Command
 */
abstract class AbstractSeedCommand extends AbstractBaseCommand
{
    /**
     * AbstractSeedCommand constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param string|null $dataDir
     */
    public function __construct(
        protected EntityManagerInterface $entityManager, protected readonly ?string $dataDir = null
    )
    {
        parent::__construct();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        try {
            foreach ($this->executeSeed($input) as $result) {
                $output->writeln($result);
            };
        } catch (Throwable) {

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * @param InputInterface $input
     * @returns string[]
     */
    abstract protected function executeSeed(InputInterface $input): array;
}
