<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Zend\Form\FormElementManager;

class AjouterFicheMetierFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AjouterFicheMetierHydrator::class);

        /** @var FicheMetierService $ficheMetierService */
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

        /** @var AjouterFicheMetierForm $form */
        $form = new AjouterFicheMetierForm();
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}