<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AgentForm extends Form {

    public function init()
    {
        //Quotité
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => 'Quotité travaillée* :',
                'empty_option' => "Sélectionner une quotité ...",
                'value_options' => [
                    20 => ' 20 %',
                    30 => ' 30 %',
                    40 => ' 40 %',
                    50 => ' 50 %',
                    60 => ' 60 %',
                    70 => ' 70 %',
                    80 => ' 80 %',
                    90 => ' 90 %',
                   100 => '100 %',
                ],
            ],
            'attributes' => [
                'id'                => 'quotite',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        //Bouton
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