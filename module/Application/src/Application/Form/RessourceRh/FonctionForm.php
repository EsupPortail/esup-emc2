<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class FonctionForm extends Form {
    use DomaineServiceAwareTrait;

    public function init()
    {
        // Domaine
        $this->add([
            'type' => Select::class,
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine* :",
                'empty_option' => "Sélectionner un domaine ...",
                'value_options' => [],//$this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaine',
                'class'             => 'bootstrap-selectpicker show-tick',
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