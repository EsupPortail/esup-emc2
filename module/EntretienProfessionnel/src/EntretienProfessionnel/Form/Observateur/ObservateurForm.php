<?php

namespace EntretienProfessionnel\Form\Observateur;

use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenUtilisateur\Entity\Db\User;

class ObservateurForm extends Form
{

    use ProvidesObjectManager;

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
            'entretien' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Aucun entretien professionnel sélectionné",
                        ],
                        'callback' => function ($value, $context = []) {
                            if ($context['entretien']['id'] == "") return false;
                            $user = $this->getObjectManager()->getRepository(EntretienProfessionnel::class)->findOneBy(['id' => $context['entretien']['id']]);
                            if ($user == null) return false;
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'user' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Aucun·e observateur·trice sélectionné·e",
                        ],
                        'callback' => function ($value, $context = []) {
                            if ($context['user']['id'] == "") return false;
                            $user = $this->getObjectManager()->getRepository(User::class)->findOneBy(['id' => $context['user']['id']]);
                            if ($user == null) return false;
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'description' => ['required' => false,],
        ]));

    }
}