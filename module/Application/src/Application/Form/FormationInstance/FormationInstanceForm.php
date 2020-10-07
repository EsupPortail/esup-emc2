<?php

namespace Application\Form\FormationInstance;

use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationInstanceForm extends Form {

    public function init()
    {
        /** Complement */
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'form-control description type2',
            ],
        ]);
        /** taille liste principale */
        $this->add([
            'type' => Number::class,
            'name' => 'principale',
            'options' => [
                'label' => "Nombre de place en liste principale * :",
            ],
            'attributes' => [
                'id' => 'principale',
            ],
        ]);
        /** taille liste complementaire */
        $this->add([
            'type' => Number::class,
            'name' => 'complementaire',
            'options' => [
                'label' => "Nombre de place en liste complémentaire * :",
            ],
            'attributes' => [
                'id' => 'complementaire',
            ],
        ]);
        /** taille liste complementaire */
        $this->add([
            'type' => Text::class,
            'name' => 'lieu',
            'options' => [
                'label' => "Lieu de la formation * :",
            ],
            'attributes' => [
                'id' => 'lieu',
            ],
        ]);
        //button
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'description'               => [ 'required' => false, ],
            'principale'                => [ 'required' => true,  ],
            'complementaire'            => [ 'required' => true,  ],
            'lieu'                      => [ 'required' => true,  ],
        ]));
    }
}