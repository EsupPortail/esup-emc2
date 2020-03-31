<?php

namespace Application\Form\ModifierDescription;

use Zend\Form\Element\Button;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ModifierDescriptionForm extends Form {

    public function init()
    {
        //libelle
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'type2 form-control',
            ],
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'description' => [ 'required' => false, ],
        ]));
    }
}