<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Assertion\EntretienProfessionnelAssertion;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;


class EntretienProfessionnelArrayViewHelperFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EntretienProfessionnelArrayViewHelper
    {
        $assertion = $container->get(EntretienProfessionnelAssertion::class);

        /** @var PhpRenderer $view */
        $helper = new EntretienProfessionnelArrayViewHelper();
        $helper->setAssertion($assertion);
        return $helper;
    }
}