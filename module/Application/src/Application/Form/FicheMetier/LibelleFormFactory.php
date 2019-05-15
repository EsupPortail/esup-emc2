<?php

namespace Application\Form\FicheMetier;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Form\FormElementManager;

class LibelleFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var LibelleHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(LibelleHydrator::class);

        /** @var RessourceRhService $ressourceRhService */
        $ressourceRhService = $manager->getServiceLocator()->get(RessourceRhService::class);

        $form = new LibelleForm();
        $form->setRessourceRhService($ressourceRhService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}