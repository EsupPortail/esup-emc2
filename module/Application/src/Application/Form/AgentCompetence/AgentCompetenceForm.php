<?php

namespace Application\Form\AgentCompetence; 

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentCompetenceForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'competence',
            'type' => Select::class,
            'options' => [
                'label' => 'Competence* : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une compétence ... ",
                'value_options' => $this->getCompetenceService()->getCompetencesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'competence',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //niveau
        $this->add([
            'name' => 'niveau',
            'type' => Select::class,
            'options' => [
                'label' => 'Niveau* : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un niveau de maîtrise ... ",
                'value_options' => [
                    'à acquérir'    => "À acquérir",
                    'à développer'  => "À développer",
                    'maîtrise'      => "Maîtrise",
                    'expert'        => "Expert",
                ],
            ],
            'attributes' => [
                'id'                => 'niveau',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'competence' => [
                'required' => true,
            ],
            'niveau' => [
                'required' => true,
            ],
        ]));
    }
}