<?php

namespace Application\Form\RessourceRh;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Zend\Form\FormElementManager;

class DomaineFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $manager->getServiceLocator()->get(FamilleProfessionnelleService::class);

        /** @var DomaineHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(DomaineHydrator::class);

        $form = new DomaineForm();
        $form->setFamilleProfessionnelleService($familleService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}