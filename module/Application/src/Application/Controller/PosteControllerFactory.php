<?php

namespace Application\Controller;

use Application\Form\Poste\PosteForm;
use Application\Service\Poste\PosteService;
use Interop\Container\ContainerInterface;

class PosteControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var PosteService $posteService
         */
        $posteService    = $container->get(PosteService::class);

        /**
         * @var PosteForm $posteForm
         */
        $posteForm = $container->get('FormElementManager')->get(PosteForm::class);

        /** @var PosteController $controller */
        $controller = new PosteController();
        $controller->setPosteService($posteService);
        $controller->setPosteForm($posteForm);
        return $controller;
    }
}