<?php

namespace Mailing\Form\MailType;

use Zend\Form\Element\Radio;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MailTypeForm extends Form {

    public function init()
    {
        //CODE
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "CODE * :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //LIBELLE
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle * :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //DESCRIPTION
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
                'class' => 'form-control description',
            ],
        ]);
        //ACTIF
        $this->add([
            'name' => 'actif',
            'type' => Radio::class,
            'options' => [
                'label' => 'Le type de mail est actif * :',
                'value_options' => [
                    1 => "Actif",
                    0 => "Inactif",
                ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'actif',
                'class' => 'form-control',
            ],
        ]);
        //BUTTON
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
                'code'          => [ 'required' => true,   ],
                'libelle'       => [ 'required' => true,   ],
                'description'   => [ 'required' => false,  ],
                'actif'         => [ 'required' => true,   ],
            ])
        );
    }
}