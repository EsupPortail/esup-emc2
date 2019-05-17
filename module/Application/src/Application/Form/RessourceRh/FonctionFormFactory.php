<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Zend\Form\FormElementManager;

class FonctionFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $manager->getServiceLocator()->get(DomaineService::class);

        /** @var FonctionHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(FonctionHydrator::class);

        /** @var FonctionForm $form */
        $form = new FonctionForm();
        $form->setDomaineService($domaineService);
        $form->init();
        $form->setHydrator($hydrator);
        return $form;
    }
}