<?php

namespace Formation\Controller;


use Formation\Form\Lieu\LieuForm;
use Formation\Service\Lieu\LieuService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class LieuControllerFactory  {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): LieuController
    {
        /**
         * @var LieuService $lieuService
         * @var LieuForm $lieuForm
         */
        $lieuService = $container->get(LieuService::class);
        $lieuForm = $container->get('FormElementManager')->get(LieuForm::class);

        $controller = new LieuController();
        $controller->setLieuForm($lieuForm);
        $controller->setLieuService($lieuService);
        return $controller;
    }


}