<?php

namespace Application\Form\FicheMetier;

use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class LibelleForm extends Form {
    use RessourceRhServiceAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Libellé du métier :",
                'empty_option' => "Sélectionner un metier ...",
                'value_options' => $this->getRessourceRhService()->getMetiersTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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