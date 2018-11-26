<?php

namespace Application\Form\RessourceRh;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class CorpsForm extends Form {

    public function init()
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        // libelle
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
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer le corps',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}