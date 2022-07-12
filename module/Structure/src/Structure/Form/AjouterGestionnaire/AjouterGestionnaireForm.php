<?php

namespace Structure\Form\AjouterGestionnaire;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AjouterGestionnaireForm extends Form {

    /** @var string */
    private $urlGestionnaire;

    /**
     * @param string $urlGestionnaire
     * @return AjouterGestionnaireForm
     */
    public function setUrlGestionnaire(string $urlGestionnaire)
    {
        $this->urlGestionnaire = $urlGestionnaire;
        return $this;
    }

    public function init()
    {
        //Agent
        $gestionnaire = new SearchAndSelect('gestionnaire', ['label' => "Gestionnaire * :"]);
        $gestionnaire
            ->setAutocompleteSource($this->urlGestionnaire)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'gestionnaire',
                'placeholder' => "Agent à ajouter comme gestionnaire...",
            ]);
        $this->add($gestionnaire);

        // periode
        $this->add([
            'name' => 'HasPeriode',
            'type' => HasPeriodeFieldset::class,
            'attributes' => [
                'id' => 'periode',
            ]
        ]);

        //Submit
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
            'gestionnaire'      => [ 'required' => true,  ],
            'HasPeriode' => ['required' => false, ],
        ]));
    }

}