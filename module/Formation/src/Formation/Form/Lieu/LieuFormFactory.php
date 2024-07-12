<?php

namespace Formation\Form\Lieu;

use Formation\Service\Lieu\LieuService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LieuFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LieuForm
    {
        /**
         * @var LieuService $lieuService
         * @var LieuHydrator $hydrator
         */
        $lieuService = $container->get(LieuService::class);
        $hydrator = $container->get('HydratorManager')->get(LieuHydrator::class);

        $form = new LieuForm();
        $form->setHydrator($hydrator);
        $form->setLieuService($lieuService);
        return $form;
    }
}