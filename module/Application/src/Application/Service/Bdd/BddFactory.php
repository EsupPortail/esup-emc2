<?php

namespace Application\Service\Bdd;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Unicaen\BddAdmin\Bdd;

class BddFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     *
     * @param ContainerInterface $container
     *
     * @return Bdd
     */
    public function __invoke(ContainerInterface $container): Bdd
    {
        $config = $container->get('Config')['doctrine']['connection']['orm_default'];
        $configOptions = $container->get('Config')['bdd-admin'];

        $bdd = new Bdd($config['params']);
        $bdd->setOptions($configOptions);

        return $bdd;
    }
}