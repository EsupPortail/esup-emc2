<?php

namespace Fichier\Form\Upload;

use Fichier\Service\Nature\NatureService;
use Zend\Form\FormElementManager;

class UploadFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var UploadHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(UploadHydrator::class);

        /**
         * @var NatureService $natureService
         */
        $natureService = $manager->getServiceLocator()->get(NatureService::class);

        /** @var UploadForm $form */
        $form = new UploadForm();
        $form->setNatureService($natureService);
        $form->setHydrator($hydrator);
        return $form;
    }
}