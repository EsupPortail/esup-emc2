<?php

namespace EntretienProfessionnel\Form\Observateur;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class ObservateurForm extends Form
{

    private ?string $urlUser = null;

    public function setUrlUser(?string $urlUser): void
    {
        $this->urlUser = $urlUser;
    }


    public function init(): void
    {
        // Utilisateur
        $utilisateur = new SearchAndSelect('user', ['label' => "Utilisateur·trice * :"]);
        $utilisateur
            ->setAutocompleteSource($this->urlUser)
            ->setSelectionRequired()
            ->setAttributes([
                'id' => 'user',
                'placeholder' => "Utilisateur·trice ...",
            ]);
        $this->add($utilisateur);

        // Description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "tinymce",
            ],
        ]);

        // Bouton
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        // Input filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'utilisateur' => ['required' => true,],
            'description' => ['required' => false,],
        ]));

    }
}