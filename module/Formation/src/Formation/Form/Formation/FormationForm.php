<?php

namespace Formation\Form\Formation;

use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationTheme\FormationThemeServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationForm extends Form {
    use FormationGroupeServiceAwareTrait;
    use FormationThemeServiceAwareTrait;

    public function init() {

        //libelle
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
        //theme
        $this->add([
            'name' => 'theme',
            'type' => Select::class,
            'options' => [
                'label' => 'Thème de la formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un thème ...',
                'value_options' => $this->getFormationThemeService()->getFormationsThemesAsOptions(),
            ],
            'attributes' => [
                'class' => 'description form-control',
                'style' => 'height:300px;',
            ]
        ]);
        //groupe
        $this->add([
            'name' => 'groupe',
            'type' => Select::class,
            'options' => [
                'label' => 'Groupe de la formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un groupe ...',
                'value_options' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            ],
            'attributes' => [
                'class' => 'description form-control',
                'style' => 'height:300px;',
            ]
        ]);
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        //lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien :",
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);
        //submit
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

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'groupe'                 => [ 'required' => false,  ],
            'theme'                 => [ 'required' => false,  ],
            'description'           => [ 'required' => false,  ],
            'lien'                  => [ 'required' => false,  ],
        ]));
    }
}