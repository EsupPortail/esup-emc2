<?php

namespace Application\Form\Competence;

use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class CompetenceForm extends Form {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    public function init()
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //precision
        $this->add([
            'type' => Text::class,
            'name' => 'precision',
            'options' => [
                'label' => "Précision :",
            ],
            'attributes' => [
                'id' => 'precision',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "type2",
            ],
        ]);
        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de la compétence :",
                'empty_option' => "Sélectionner le type de la compétence ...",
                'value_options' => $this->getCompetenceTypeService()->getCompetencesTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //theme
        $this->add([
            'type' => Select::class,
            'name' => 'theme',
            'options' => [
                'label' => "Thème de compétence :",
                'empty_option' => "Sélectionner le thème de la compétence ...",
                'value_options' => $this->getCompetenceThemeService()->getCompetencesThemesAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => [ 'required' => true,  ],
            'precision' => [ 'required' => false,  ],
            'description' => [ 'required' => false,  ],
            'type'    => [ 'required' => false, ],
            'theme'   => [ 'required' => false, ],
        ]));
    }
}