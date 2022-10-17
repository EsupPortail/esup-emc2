<?php

namespace Fichier\Form\Upload;

use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;
use Laminas\Form\FormElementManager;

class UploadFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var UploadHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(UploadHydrator::class);

        /**
         * @var NatureService $natureService
         */
        $natureService = $container->get(NatureService::class);

        /** @var UploadForm $form */
        $form = new UploadForm();
        $form->setNatureService($natureService);
        $form->setHydrator($hydrator);
        return $form;
    }
}