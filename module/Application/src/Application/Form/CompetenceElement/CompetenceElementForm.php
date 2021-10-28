<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CompetenceElementForm extends Form {
    use CompetenceServiceAwareTrait;
    use MaitriseNiveauServiceAwareTrait;

    public function init()
    {
        //competence
        $this->add([
            'name' => 'competence',
            'type' => Select::class,
            'options' => [
                'label' => 'Compétences * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
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
                'value_options' => $this->getMaitriseNiveauService()->getMaitrisesNiveauxAsOptions('Competence'),
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