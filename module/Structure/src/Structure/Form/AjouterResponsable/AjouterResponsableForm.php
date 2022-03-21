<?php

namespace Structure\Form\AjouterResponsable;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterResponsableForm extends Form {

    /** @var string */
    private $urlResponsable;

    /**
     * @param string $urlResponsable
     * @return AjouterResponsableForm
     */
    public function setUrlResponsable(string $urlResponsable)
    {
        $this->urlResponsable = $urlResponsable;
        return $this;
    }

    public function init()
    {
        //Agent
        $responsable = new SearchAndSelect('responsable', ['label' => "Responsable * :"]);
        $responsable
            ->setAutocompleteSource($this->urlResponsable)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'responsable',
                'placeholder' => "Agent à ajouter comme gestionnaire...",
            ]);
        $this->add($responsable);

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
            'responsable' => [ 'required' => true,  ],
            'HasPeriode'  => ['required' => false, ],
        ]));
    }

}