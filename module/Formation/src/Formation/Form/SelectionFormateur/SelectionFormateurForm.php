<?php

namespace Formation\Form\SelectionFormateur;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class SelectionFormateurForm extends Form
{
    use FormationServiceAwareTrait;

    private ?string $urlFormateur = null;
    public function setUrlFormateur(string $urlFormateur): void
    {
        $this->urlFormateur = $urlFormateur;
    }

    public function init(): void
    {

        $formateur = new SearchAndSelect('formateur', ['label' => "Formateur·trice ou organisme * :"]);
        $formateur
            ->setAutocompleteSource($this->urlFormateur)
            ->setSelectionRequired()
            ->setAttributes([
                'id' => 'formateur',
                'placeholder' => "Rechercher ...",
            ]);
        $this->add($formateur);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'id' => 'enregistrer',
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'formateur' => ['required' => true,],
        ]));
    }
}