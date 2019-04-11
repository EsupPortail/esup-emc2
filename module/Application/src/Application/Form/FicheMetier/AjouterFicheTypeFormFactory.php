<?php

namespace Application\Form\FicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Form\FormElementManager;

class AjouterFicheTypeFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AjouterFicheTypeHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AjouterFicheTypeHydrator::class);

        /** @var FicheMetierService $ficheMetierService */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

        /** @var AjouterFicheTypeForm $form */
        $form = new AjouterFicheTypeForm();
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}