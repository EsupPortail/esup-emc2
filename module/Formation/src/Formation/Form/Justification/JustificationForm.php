<?php

namespace Formation\Form\Justification;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class JustificationForm extends Form {


    public function init(): void
    {
        //missions
        $this->add([
            'name' => 'etape',
            'type' => Hidden::class,
        ]);
        //missions
        $this->add([
            'name' => 'missions',
            'type' => Textarea::class,
            'options' => [
                'label' => "Missions du poste en adéquation avec la demande de formation <span class='icon icon-obligatoire' title='champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'missions',
                'class' => 'tinymce',
            ]
        ]);
        //description
        $this->add([
            'name' => 'justification',
            'type' => Textarea::class,
            'options' => [
                'label' => "Justification / Motivation <span class='icon icon-obligatoire' title='champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'justification',
                'class' => 'tinymce',
            ]
        ]);
        //rqth
        $this->add( [
            'name' => 'rqth',
            'type' => Radio::class,
            'options' => [
                'label' => "Êtes vous un agent en situation de handicap? <span class='icon icon-obligatoire' title='champ obligatoire'></span>",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'rqth',
                'class' => '',
            ]
        ]);
        //precision_rqth
        $this->add([
            'name' => 'precision_rqth',
            'type' => Textarea::class,
            'options' => [
                'label' => "Si oui, de quel accompagnement avez-vous besoin ?",
                'label_options' => ['disable_html_escape' => true,],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'precision_rqth',
                'class' => 'tinymce',
            ]
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
        $this->setInputFilter((new Factory())->createInputFilter([
            'missions' => [ 'missions' => true,  ],
            'justification' => [ 'required' => true,  ],
            'etape' => [ 'required' => true,  ],
            'rqth' => [ 'required' => true,  ],
            'precision_rqth' => [ 'required' => false,  ],
        ]));
    }

}