<?php

namespace Application\Form\Metier;

use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
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
            'name' => 'domaines',
            'options' => [
                'label' => "Domaine professionnel* :",
                'empty_option' => "Sélectionner des domaines ...",
                'value_options' => $this->getDomaineService()->getDomainesAsOptions(),
            ],
            'attributes' => [
                'id' => 'domaines',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
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
        // expertise
        $this->add([
            'type' => Checkbox::class,
            'name' => 'expertise',
            'options' => [
                'label' => "Associable à des expertises métiers",
            ],
            'attributes' => [
                'id' => 'expertise',
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
            'domaines'          => [ 'required' => true,  ],
            'libelle'           => [ 'required' => true,  ],
            'expertise'         => [ 'required' => false, ],
        ]));
    }
}