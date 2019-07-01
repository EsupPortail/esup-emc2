<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Zend\Form\FormElementManager;

class MetierFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MetierHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MetierHydrator::class);

        /** @var DomaineService $domaineService */
        $domaineService = $manager->getServiceLocator()->get(DomaineService::class);

        $form = new MetierForm();
        $form->setDomaineService($domaineService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}