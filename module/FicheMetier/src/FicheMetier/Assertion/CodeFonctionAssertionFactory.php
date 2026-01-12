<?php

namespace FicheMetier\Assertion;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class CodeFonctionAssertionFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeFonctionAssertion
    {
        /**
         * @var ParametreService $parametreService
         */
        $parametreService = $container->get(ParametreService::class);

        $assertion = new CodeFonctionAssertion();
        $assertion->setParametreService($parametreService);
        return $assertion;
    }
}
