<?php

namespace Formation\View\Helper;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class DemandeExterneArrayViewHelperFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DemandeExterneArrayViewHelper
{
    /**
     * @var ParametreService $parametreService
     */
    $parametreService = $container->get(ParametreService::class);

    $helper = new DemandeExterneArrayViewHelper();
    $helper->setParametreService($parametreService);
    return $helper;
}
}