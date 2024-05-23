<?php

namespace Structure\Form\Observateur;

use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class ObservateurForm extends Form
{

    private ?string $urlStructure = null;

    public function setUrlStructure(?string $urlStructure): void
    {
        $this->urlStructure = $urlStructure;
    }

    private ?string $urlUtilisateur = null;

    public function setUrlUtilisateur(?string $urlUtilisateur): void
    {
        $this->urlUtilisateur = $urlUtilisateur;
    }

    public function init() : void
    {

        //structure
        $structure = new SearchAndSelect('structure-sas', ['label' => "Service/composante/direction d'affectation * :"]);
        $structure
            ->setAutocompleteSource($this->urlStructure)
            ->setAttributes([
                'id' => 'structure-sas',
                'placeholder' => "Nom de la structure ...",
            ]);
        $this->add($structure);
        //utilisateur
        $structure = new SearchAndSelect('utilisateur-sas', ['label' => "Utilisateur·trice * :"]);
        $structure
            ->setAutocompleteSource($this->urlUtilisateur)
            ->setAttributes([
                'id' => 'utilisateur-sas',
                'placeholder' => "Dénomination de l'utilisateur·trice ...",
            ]);
        $this->add($structure);
        //description
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
                'id' => 'description',
            ]
        ]);
        //botton

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
        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'structure-sas'          => [ 'required' => true,  ],
            'utilisateur-sas'        => [ 'required' => true,  ],
            'description'        => [ 'required' => false, ],
        ]));


    }

}