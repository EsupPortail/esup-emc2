<?php

namespace Mailing\Form\MailContent;

use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MailContentForm extends Form {

    public function init() {

        //SUJET (Textarea car macro !!!)
        $this->add([
            'name' => 'sujet',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Sujet du mail :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'sujet',
                'class' => 'form-control sujet',
            ],
        ]);
        //CORPS
        $this->add([
            'name' => 'corps',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Corps du mail :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'corps',
                'class' => 'form-control corps',
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

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'sujet'          => [ 'required' => false,    ],
            'corps'          => [ 'required' => false,    ],
            ])
        );
    }
}