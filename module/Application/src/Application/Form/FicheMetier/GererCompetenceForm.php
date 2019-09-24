<?php

namespace Application\Form\FicheMetier; 

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class GererCompetenceForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'competences',
            'type' => Select::class,
            'options' => [
                'label' => 'Compétences : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'value_options' => $this->getCompetenceService()->getCompetencesAsGroupOptions(),
            ],
            'attributes' => [
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
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
            'competences' => [
                'required' => false,
            ],
        ]));
    }
}