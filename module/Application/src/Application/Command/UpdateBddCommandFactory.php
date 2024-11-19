<?php

namespace Application\Command;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Unicaen\BddAdmin\Bdd;

class UpdateBddCommandFactory extends Command
{
    /**
     * @param ContainerInterface $container
     *
     * @return UpdateBddCommand
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UpdateBddCommand
    {
        $bdd = $container->get(Bdd::class);

        $command = new UpdateBddCommand();

        $command->setBdd($bdd);

        return $command;
    }
}
