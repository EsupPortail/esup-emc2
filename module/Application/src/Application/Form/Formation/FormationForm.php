<?php

namespace Application\Form\Formation;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationForm extends Form {

    public function init() {

        //libelle
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
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        //lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien :",
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false,  ],
            'lien'                  => [ 'required' => false,  ],
        ]));
    }
}