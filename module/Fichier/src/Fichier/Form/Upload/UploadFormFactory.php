<?php

namespace Fichier\Form\Upload;

use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;
use Laminas\Form\FormElementManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class UploadFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): UploadForm
    {
        /** @var UploadHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(UploadHydrator::class);

        /**
         * @var NatureService $natureService
         */
        $natureService = $container->get(NatureService::class);

        $form = new UploadForm();
        $form->setNatureService($natureService);
        $form->setHydrator($hydrator);
        return $form;
    }
}