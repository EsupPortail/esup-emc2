<?php

namespace Application\Form\FicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Form\FormElementManager;

class AssocierMetierTypeFormFactory {

    public function __invoke(FormElementManager $manager)
    {

        /** @var FicheMetierService $ficheMetierService */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);
        /** @var AssocierMetierTypeHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AssocierMetierTypeHydrator::class);

        /** @var AssocierMetierTypeForm $form */
        $form = new AssocierMetierTypeForm();
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}