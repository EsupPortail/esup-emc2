<?php

namespace Application\Form\EntretienProfesionnelFormulaire;

use Interop\Container\ContainerInterface;

class EntretienProfessionnelFormulaireFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelFormulaireForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var EntretienProfessionnelFormulaireForm $form */
        $form = new EntretienProfessionnelFormulaireForm();
        return $form;
    }
}