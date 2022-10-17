<?php

namespace UnicaenValidation\Form\ValidationType;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ValidationTypeForm extends Form {

    public function init()
    {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code* :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //refusable
        $this->add([
            'type' => Checkbox::class,
            'name' => 'refusable',
            'options' => [
                'label' => "Peut-être refusable",
            ],
            'attributes' => [
                'id' => 'refusable',
            ],
        ]);
        //submit
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
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'code'       => [ 'required' => true, ],
            'libelle'    => [ 'required' => true, ],
            'refusable'  => [ 'required' => false, ],
        ]));
    }
}