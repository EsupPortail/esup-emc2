<?php

namespace Application\Form\Activite;

use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ActiviteForm extends Form {
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FormationServiceAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // description
//        $this->add([
//            'name' => 'description',
//            'type' => 'textarea',
//            'options' => [
//                'label' => 'Description : ',
//                'label_attributes' => [
//                    'class' => 'control-label',
//                ],
//            ],
//            'attributes' => [
//                'class' => 'type2 form-control',
//            ]
//        ]);
        //application
//        $this->add([
//            'name' => 'applications',
//            'type' => Select::class,
//            'options' => [
//                'label' => 'Applications : ',
//                'label_attributes' => [
//                    'class' => 'control-label',
//                ],
//                'value_options' => $this->getApplicationOptions()
//            ],
//            'attributes' => [
//                'class' => 'description form-control',
//                'style' => 'height:300px;',
//                'multiple' => 'multiple',
//            ]
//        ]);
        //competence
//        $this->add([
//            'name' => 'competences',
//            'type' => Select::class,
//            'options' => [
//                'label' => 'Compétences : ',
//                'label_attributes' => [
//                    'class' => 'control-label',
//                ],
//                'value_options' => $this->getCompetenceService()->getCompetencesAsGroupOptions(),
//            ],
//            'attributes' => [
//                'class' => 'description form-control',
//                'style' => 'height:300px;',
//                'multiple' => 'multiple',
//            ]
//        ]);
        // formation
//        $this->add([
//            'type' => Select::class,
//            'name' => 'formations',
//            'options' => [
//                'label' => "Formations associées :",
//                'empty_option' => "Sélectionner la ou les formations ...",
//                'value_options' => $this->getFormationService()->getFormationsThemesAsGroupOptions(),
//            ],
//            'attributes' => [
//                'id' => 'formations',
//                'class'             => 'bootstrap-selectpicker show-tick',
//                'data-live-search'  => 'true',
//                'multiple'          => 'multiple',
//            ],
//        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
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
            'libelle'               => [ 'required' => true,  ],
//            'description'           => [ 'required' => true,  ],
//            'applications'          => [ 'required' => false,  ],
//            'competences'           => [ 'required' => false,  ],
//            'formations'            => [ 'required' => false,  ],
        ]));
    }
}