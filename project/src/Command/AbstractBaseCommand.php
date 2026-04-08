<?php

declare(strict_types=1);

namespace App\Command;

use RuntimeException;
use Symfony\Component\Console\Command\Command;

/**
 * Class AbstractCommand
 *
 * @package App\Command
 */
abstract class AbstractBaseCommand extends Command
{
    protected const string|null DESCRIPTION = null;
    protected const string|null NAME = null;

    /**
     * @returns void
     * @throws RuntimeException
     */
    protected function configure(): void
    {
        if (static::NAME === null || static::DESCRIPTION === null) {

            throw new RuntimeException('Error');
        }
        $this->setName(static::NAME)->setDescription(static::DESCRIPTION);
    }
}
