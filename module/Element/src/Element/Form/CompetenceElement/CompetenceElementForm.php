<?php

namespace Element\Form\CompetenceElement;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CompetenceElementForm extends Form {
    use CompetenceServiceAwareTrait;
    use NiveauServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'competence',
            'type' => Select::class,
            'options' => [
                'label' => "Compétences  <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [ 'class' => 'control-label', ],
                'empty_option' => "Sélectionner une compétence ... ",
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
                'label' => 'Niveau  : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un niveau ... ",
                'value_options' => $this->getNiveauService()->getMaitrisesNiveauxAsOptions('Competence'),
            ],
            'attributes' => [
                'id'                => 'niveau',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        $this->add([
            'type' => Checkbox::class,
            'name' => 'clef',
            'options' => [
                'label' => "Est clef",
            ],
            'attributes' => [
                'id'                => 'clef',
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
            'niveau'          => [ 'required' => false, ],
            'clef'          => [ 'required' => false, ],
        ]));
    }

    public function masquerClef()
    {
        $this->remove('clef');
    }
}