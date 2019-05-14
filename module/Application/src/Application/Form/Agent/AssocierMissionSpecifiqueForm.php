<?php

namespace Application\Form\Agent;

use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AssocierMissionSpecifiqueForm extends Form {
    use RessourceRhServiceAwareTrait;

    public function init()
    {
        // quotite
        $this->add([
            'type' => Select::class,
            'name' => 'mission',
            'options' => [
                'label' => "Mission spécifique* :",
                'value_options' => $this->getRessourceRhService()->getMisssionsSpecifiquesAsOption(),
            ],
            'attributes' => [
                'id' => 'mission',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'associer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer',
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