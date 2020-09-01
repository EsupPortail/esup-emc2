<?php

namespace Application\Form\FormationJournee;

use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\Text;
use Zend\Form\Element\Time;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationJourneeForm extends Form {

    public function init()
    {
        //jour
        $this->add([
            'type' => Date::class,
            'name' => 'jour',
            'options' => [
                'label' => "Jour de la formation * :",
            ],
            'attributes' => [
                'id' => 'jour',
            ],
        ]);
        //debut
        $this->add([
            'type' => Time::class,
            'name' => 'debut',
            'options' => [
                'label' => "Début de la journée * :",
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);
        //fin
        $this->add([
            'type' => Time::class,
            'name' => 'fin',
            'options' => [
                'label' => "Fin de la journée * :",
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
                'label' => '<i class="fas fa-save"></i> Enregistrer' ,
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
            'jour'            => [ 'required' => true, ],
            'debut'           => [ 'required' => true, ],
            'fin'             => [ 'required' => true, ],
            'lieu'            => [ 'required' => true, ],
        ]));
    }
}