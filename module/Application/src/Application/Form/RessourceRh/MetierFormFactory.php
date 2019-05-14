<?php

namespace Application\Form\RessourceRh;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Form\FormElementManager;

class MetierFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MetierHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MetierHydrator::class);

        /** @var RessourceRhService $ressourceService */
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);

        $form = new MetierForm();
        $form->setRessourceRhService($ressourceService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}