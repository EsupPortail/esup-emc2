<?php

namespace Application\Command;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Unicaen\BddAdmin\Bdd;

class UpdateDdlCommandFactory extends Command
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UpdateDdlCommand
    {
        $bdd = $container->get(Bdd::class);

        $command = new UpdateDdlCommand();

        $command->setBdd($bdd);

        return $command;
    }
}
