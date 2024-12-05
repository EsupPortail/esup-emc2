<?php

namespace FicheMetier\Form\CodeFonction;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CodeFonctionForm extends Form
{

    public function init(): void
    {
        $this->add([
            'type' => Text::class,
            'name' => 'code-fonction',
            'options' => [
                'label' => "Code fonction  : ",
            ],
            'attributes' => [
                'id' => 'code-fonction',
            ],
        ]);
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
            'code-fonction'         => [ 'required' => false,  ],
        ]));
    }

}