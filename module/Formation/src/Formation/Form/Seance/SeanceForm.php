<?php

namespace Formation\Form\Seance;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\DateTime;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SeanceForm extends Form
{

    public function init()
    {
        //jour
        $this->add([
            'type' => DateTime::class,
            'name' => 'jour',
            'options' => [
                'label' => "Jour de la formation * :",
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'jour',
            ],
        ]);
        //debut
        $this->add([
            'type' => DateTime::class,
            'name' => 'debut',
            'options' => [
                'label' => "Début de la journée * :",
                'format' => 'H:i',
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);
        //fin
        $this->add([
            'type' => DateTime::class,
            'name' => 'fin',
            'options' => [
                'label' => "Fin de la journée * :",
                'format' => 'H:i'
            ],
            'attributes' => [
                'id' => 'fin',
            ],
        ]);
        //salle
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

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'jour' => ['required' => true,],
            'debut' => ['required' => true,],
            'fin' => ['required' => true,],
            'lieu' => ['required' => true,],
        ]));
    }
}