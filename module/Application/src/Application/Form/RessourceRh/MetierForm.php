<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MetierForm extends Form {
    use DomaineServiceAwareTrait;

    public function init()
    {
        //domaine
        $this->add([
            'type' => Select::class,
            'name' => 'fonction',
            'options' => [
                'label' => "Fonction :",
                'empty_option' => "Sélectionner une fonction ...",
                'value_options' => [
                    'Soutien' => 'Soutien',
                    'Support' => 'Support',
                ],
            ],
            'attributes' => [
                'id' => 'fonction',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //fonction
        $this->add([
            'type' => Select::class,
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine professionnel* :",
                'empty_option' => "Sélectionner un domaine ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'fonction',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien vers Referens :",
            ],
            'attributes' => [
                'id' => 'lien',
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

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'fonction'          => [ 'required' => true,  ],
            'domaine'           => [ 'required' => true,  ],
            'libelle'           => [ 'required' => true,  ],
            'lien'              => [ 'required' => false, ],
        ]));
    }
}