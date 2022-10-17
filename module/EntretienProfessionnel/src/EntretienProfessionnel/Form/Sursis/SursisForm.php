<?php

namespace EntretienProfessionnel\Form\Sursis;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Laminas\Form\Element\Date;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

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
            ],
            'attributes' => [
                'id' => 'date',

            ],
        ]);
        //description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
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
            'description'           => [ 'required' => false,  ],
        ]));
    }
}