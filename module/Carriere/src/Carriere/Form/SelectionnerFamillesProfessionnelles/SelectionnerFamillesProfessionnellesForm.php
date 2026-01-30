<?php

namespace Carriere\Form\SelectionnerFamillesProfessionnelles;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;

class SelectionnerFamillesProfessionnellesForm extends Form
{
    use FamilleProfessionnelleServiceAwareTrait;

    public function init(): void
    {

        // libelle
        $this->add([
            'type' => Select::class,
            'name' => 'famillesprofessionnelles',
            'options' => [
                'label' => "Libellé de la famille professionnelle :",
                'empty_option' => "Sélectionner une famille professionnelle",
                'value_options' =>
                    $this->getFamilleProfessionnelleService()->getFamillesProfessionnellesAsOptions(),
            ],
            'attributes' => [
                'id' => 'famillesprofessionnelles',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
                'multiple' => 'multiple',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}