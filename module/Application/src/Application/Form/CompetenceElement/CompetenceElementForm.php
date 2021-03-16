<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CompetenceElementForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'competence',
            'type' => Select::class,
            'options' => [
                'label' => 'Application * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une application ... ",
                'value_options' => $this->getCompetenceService()->getCompetencesAsGroupOptions(),
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
                'label' => 'Niveau * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un niveau ... ",
                'value_options' => [
                    'Aucune'         => "Aucune (0/5)",
                    'Débutant'       => "Débutant (1/5)",
                    'Apprenti'       => "Apprenti (2/5)",
                    'Intermédiaire'  => "Intermédiaire (3/5)",
                    'Confirmé'       => "Confirmé (4/5)",
                    'Expert'         => "Expert (5/5)",
                ],
            ],
            'attributes' => [
                'id'                => 'niveau',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //Année
        $this->add([
            'type' => Number::class,
            'name' => 'annee',
            'options' => [
                'label' => "Année de la formation :",
            ],
            'attributes' => [
                'id' => 'annee',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'competence'   => [ 'required' => true, ],
            'niveau'          => [ 'required' => true, ],
            'annee'         => [ 'required' => false, ],
        ]));
    }
}