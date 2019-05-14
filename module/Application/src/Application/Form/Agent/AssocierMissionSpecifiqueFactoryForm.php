<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhService;
use Zend\Form\FormElementManager;

class AssocierMissionSpecifiqueFactoryForm {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AssocierMissionSpecifiqueHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AssocierMissionSpecifiqueHydrator::class);

        /** @var RessourceRhService $ressourceService */
        $ressourceService = $manager->getServiceLocator()->get(RessourceRhService::class);

        /** @var AssocierMissionSpecifiqueForm $form */
        $form = new AssocierMissionSpecifiqueForm();
        $form->setRessourceRhService($ressourceService);
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}