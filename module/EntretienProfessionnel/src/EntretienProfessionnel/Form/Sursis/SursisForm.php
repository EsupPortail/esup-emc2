<?php

namespace EntretienProfessionnel\Form\Sursis;

use Zend\Form\Element\Date;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SursisForm extends Form
{
    public function init()
    {
        //date
        $this->add([
            'type' => Date::class,
            'name' => 'date',
            'options' => [
                'label' => "Fin du sursis * :",
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date',
            ],
        ]);
        //description
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'description form-control',
            ]
        ]);
        //button
        $this->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Enregistrer',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'date'                  => [ 'required' => true,  ],
            'description'           => [ 'required' => true,  ],
        ]));
    }
}