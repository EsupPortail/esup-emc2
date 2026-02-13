<?php

namespace FicheMetier\Form\TendanceElement;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class TendanceElementForm extends Form
{

    public function init(): void
    {
        $this->add([
            'type' => Textarea::class,
            'name' => 'texte',
            'options' => [
                'label' => "Contenu :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'class' => 'tinymce',
                'id' => 'texte',
            ],
        ]);
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Valider',
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
            'texte' => ['required' => false,],
        ]));
    }
}