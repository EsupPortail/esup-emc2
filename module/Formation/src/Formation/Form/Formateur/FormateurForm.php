<?php

namespace Formation\Form\Formateur;

use Formation\Entity\Db\Formateur;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use RuntimeException;

class FormateurForm extends Form
{
    use FormateurServiceAwareTrait;

    public function init(): void
    {
        // type
        $this->add([
            'type' => Select::class,
            'name' => 'formateur-type',
            'options' => [
                'label' => "Type de formateur <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => Formateur::TYPES
            ],
            'attributes' => [
                'id' => 'formateur-type',
                'class' => 'bootstrap-selectpicker show-tick',
            ],
        ]);
        //organisme
        $this->add([
            'type' => Text::class,
            'name' => 'organisme',
            'options' => [
                'label' => "Organisme <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'organisme',
            ],
        ]);

        //prenom
        $this->add([
            'type' => Text::class,
            'name' => 'prenom',
            'options' => [
                'label' => "Prénom <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'prenom',
            ],
        ]);
        //nom
        $this->add([
            'type' => Text::class,
            'name' => 'nom',
            'options' => [
                'label' => "Nom <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'nom',
            ],
        ]);
        //attachement
        $this->add([
            'type' => Text::class,
            'name' => 'attachement',
            'options' => [
                'label' => "Structure de rattachement / Organisme  :",
            ],
            'attributes' => [
                'id' => 'attachement',
            ],
        ]);
        //email
        $this->add([
            'type' => Text::class,
            'name' => 'email',
            'options' => [
                'label' => "Adresse électronique <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'email',
            ],
        ]);
        //email
        $this->add([
            'type' => Text::class,
            'name' => 'telephone',
            'options' => [
                'label' => "Téléphone :",
            ],
            'attributes' => [
                'id' => 'telephone',
            ],
        ]);

        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'formateur-type' => ['required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Une information obligatoire est manquante",
                        ],
                        'callback' => function ($value, $context = []) {
                            if ($context['formateur-type'] === Formateur::TYPE_FORMATEUR)
                                return ($context['nom'] !== '' and $context['prenom']);
                            if ($context['formateur-type'] === Formateur::TYPE_ORGANISME)
                                return ($context['organisme'] !== '');
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],],

            'organisme' => ['required' => false],
            'prenom' => ['required' => false],
            'nom' => ['required' => false],
            'attachement' => ['required' => false],

            'email' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Un·e formateur·trice utilise déjà cette adresse électronique",
                            ],
                            'callback' => function ($value, $context = []) {
                                $formateurs = $this->getFormateurService()->getFormateursByEmail($value);
                            },
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'telephone' => ['required' => false],
        ]));
    }
}