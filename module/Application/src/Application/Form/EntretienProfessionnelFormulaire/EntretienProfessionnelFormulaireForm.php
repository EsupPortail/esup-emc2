<?php

namespace Application\Form\EntretienProfesionnelFormulaire;

use Zend\Form\Element\MultiCheckbox;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;

class EntretienProfessionnelFormulaireForm extends Form {

    public function init()
    {
        //--- Description du poste occupé par l'agent

        // TextArea :: Mission du poste
        $this->add([
            'name' => 'mission',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Mission du poste :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'mission',
                'class' => 'form-control',
            ],
        ]);

        // MultiCheckbox :: Encadrement || Conduite de projet
        $this->add([
            'type' => MultiCheckbox::class,
            'name' => 'encadrement_projet',
            'options' => [
                'label' => 'Fonctions d’encadrement ou de conduite de projet :',
                'value_options' => [
                    [
                        'value' => 'projet',
                        'label' => "l'agent assume des fonctions de conduite de projet",
                    ],
                    [
                        'value' => 'encadrement',
                        'label' => "l'agent assume des fonctions d'encadrement ",
                    ],
                ],
            ],
        ]);
        // Number :: Encadrement A
        $this->add([
            'type' => Number::class,
            'name' => 'encadrement_A',
            'options' => [
                'label' => "Nombre d'agents encadrés de catégorie A :",
            ],
        ]);
        // Number :: Encadrement B
        $this->add([
            'type' => Number::class,
            'name' => 'encadrement_B',
            'options' => [
                'label' => "Nombre d'agents encadrés de catégorie B :",
            ],
        ]);
        // Number :: Encadrement C
        $this->add([
            'type' => Number::class,
            'name' => 'encadrement_C',
            'options' => [
                'label' => "Nombre d'agents encadrés de catégorie C :",
            ],
        ]);

        //--- Évaluation de l'année écoulée

        //TextArea :: Rappel
        $this->add([
            'name' => 'rappel',
            'type' => Textarea::class,
            'options' => [
                'label' => "Rappel des objectifs d'activités attendus :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'rappel',
                'class' => 'form-control',
            ],
        ]);
        //TextArea :: Evenement
        $this->add([
            'name' => 'evenement',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Événements survenus au cours de la période écoulée :',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'evenement',
                'class' => 'form-control',
            ],
        ]);

        //--- Valeur professionnelle et manière de servir du fonctionnaire

        // Compétence
        $this->add([
            'name' => 'competences',
            'type' => Textarea::class,
            'options' => [
                'label' => "Les compétences professionnelles et technicité : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'competences',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'select_competences',
            'type' => Select::class,
            'options' => [
                'label' => 'Compétences professionnelles et technicité : ',
                'empty_option' => "Sélectionner un niveau si nécessaire",
                'value_options' => [
                    'À acquerir' => 'À acquerir',
                    'À développer' => 'À développer',
                    'Maîtrise' => 'Maîtrise',
                    'Expert' => 'Expert',
                ],
            ],
            'attributes' => [
                'id' => 'select_competences',
                'class' => 'form-control',
            ],
        ]);

        //Contribution
        $this->add([
            'name' => 'contribution',
            'type' => Textarea::class,
            'options' => [
                'label' => "La contribution à l'activité du service : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'contribution',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'select_contribution',
            'type' => Select::class,
            'options' => [
                'label' => 'Contribution à l’activité du service : ',
                'empty_option' => "Sélectionner un niveau si nécessaire",
                'value_options' => [
                    'À acquerir' => 'À acquerir',
                    'À développer' => 'À développer',
                    'Maîtrise' => 'Maîtrise',
                    'Expert' => 'Expert',
                ],
            ],
            'attributes' => [
                'id' => 'select_contribution',
                'class' => 'form-control',
            ],
        ]);

        //Capacite
        $this->add([
            'name' => 'capacites',
            'type' => Textarea::class,
            'options' => [
                'label' => "Les capacités professionnelles et relationnelles : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'capacites',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'select_capacites',
            'type' => Select::class,
            'options' => [
                'label' => 'Capacités professionnelles et relationnelles : ',
                'empty_option' => "Sélectionner un niveau si nécessaire",
                'value_options' => [
                    'À acquerir' => 'À acquerir',
                    'À développer' => 'À développer',
                    'Maîtrise' => 'Maîtrise',
                    'Expert' => 'Expert',
                ],
            ],
            'attributes' => [
                'id' => 'select_capacites',
                'class' => 'form-control',
            ],
        ]);

        //Aptitude
        $this->add([
            'name' => 'aptitude',
            'type' => Textarea::class,
            'options' => [
                'label' => "Le cas échéant, aptitude à l'encadrement et/ou à la conduite de projets :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'aptitude',
                'class' => 'form-control',
            ],
        ]);
        $this->add([
            'name' => 'select_aptitude',
            'type' => Select::class,
            'options' => [
                'label' => 'Capacités professionnelles et relationnelles : ',
                'empty_option' => "Aptitude à l’encadrement et/ou à la conduite de projets (le cas échéant)",
                'value_options' => [
                    'À acquerir' => 'À acquerir',
                    'À développer' => 'À développer',
                    'Maîtrise' => 'Maîtrise',
                    'Expert' => 'Expert',
                ],
            ],
            'attributes' => [
                'id' => 'select_aptitude',
                'class' => 'form-control',
            ],
        ]);

        //Réalisation
        $this->add([
            'name' => 'realisation',
            'type' => Textarea::class,
            'options' => [
                'label' => "Réalisation des objectifs de l’année écoulée :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'realisation',
                'class' => 'form-control',
            ],
        ]);
        //appreciation
        $this->add([
            'name' => 'appreciation',
            'type' => Textarea::class,
            'options' => [
                'label' => "Réalisation des objectifs de l’année écoulée :",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'id' => 'appreciation',
                'class' => 'form-control',
            ],
        ]);

        //--- Aquis de l'expérience professionnelle
        //--- Objectifs fixés pour la nouvelle année
        //--- Perspectives d'évolution professionnelle
    }
}