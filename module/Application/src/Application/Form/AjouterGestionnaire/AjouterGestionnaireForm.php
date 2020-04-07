<?php

namespace Application\Form\AjouterGestionnaire;

use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterGestionnaireForm extends Form {

    /** @var string */
    private $urlGestionnaire;
    /** @var string */
    private $urlStructure;

    /**
     * @param string $urlGestionnaire
     * @return AjouterGestionnaireForm
     */
    public function setUrlGestionnaire(string $urlGestionnaire)
    {
        $this->urlGestionnaire = $urlGestionnaire;
        return $this;
    }

    /**
     * @param string $urlStructure
     * @return AjouterGestionnaireForm
     */
    public function setUrlStructure(string $urlStructure)
    {
        $this->urlStructure = $urlStructure;
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
                'placeholder' => "Gestionnaire à ajouter ...",
            ]);
        $this->add($gestionnaire);

        // structure
        $structure = new SearchAndSelect('structure', ['label' => "Service/composante/direction d'affectation * :"]);
        $structure
            ->setAutocompleteSource($this->urlStructure)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'structure',
                'placeholder' => "Nom de la structure ...",
            ]);
        $this->add($structure);

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
            'structure'         => [ 'required' => true, ],
        ]));
    }

}