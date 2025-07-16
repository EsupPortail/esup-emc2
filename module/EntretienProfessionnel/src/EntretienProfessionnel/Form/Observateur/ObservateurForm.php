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

    private ?string $urlEntretien = null;

    public function setUrlEntretien(?string $urlEntretien): void
    {
        $this->urlEntretien = $urlEntretien;
    }


    public function init(): void
    {
        // entretien
        $entretien = new SearchAndSelect('entretien', ['label' => "Entretien professionnel * :"]);
        $entretien
            ->setAutocompleteSource($this->urlEntretien)
            ->setSelectionRequired()
            ->setAttributes([
                'id' => 'entretien',
                'placeholder' => "Nom de l'agent·e passant l'entretien ...",
            ]);
        $this->add($entretien);
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
                'class' => "tinymce type2",
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
            'entretien' => ['required' => true,],
            'user' => ['required' => true,],
            'description' => ['required' => false,],
        ]));

    }
}