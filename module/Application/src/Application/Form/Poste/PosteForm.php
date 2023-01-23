<?php

namespace Application\Form\Poste;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class PosteForm extends Form  {

    public function init()
    {
        // referentiel
        $this->add([
            'type' => Text::class,
            'name' => 'referentiel',
            'options' => [
                'label' => "Référentiel :",
            ],
            'attributes' => [
                'id' => 'referentiel',
            ],
        ]);
        // intitule
        $this->add([
            'type' => Text::class,
            'name' => 'intitule',
            'options' => [
                'label' => "Intitulé  :",
            ],
            'attributes' => [
                'id' => 'intitule',
            ],
        ]);
        // referentiel
        $this->add([
            'type' => Text::class,
            'name' => 'poste_id',
            'options' => [
                'label' => "Identifiant du poste  :",
            ],
            'attributes' => [
                'id' => 'poste_id',
            ],
        ]);


        // button
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
            'referentiel'      => [ 'required' => false,  ],
            'intitule'         => [ 'required' => false,  ],
            'poste_id'         => [ 'required' => false,  ],
        ]));
    }
}