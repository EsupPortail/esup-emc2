<?php

namespace Formation\Form\Seance;

use DateTime;
use Formation\Entity\Db\Seance;
use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Time;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use UnicaenApp\Form\Element\Date;
use UnicaenApp\Form\Element\SearchAndSelect;

class SeanceForm extends Form
{
    use LieuServiceAwareTrait;


    private array $seances = [];
    private ?string $url;

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function init(): void
    {
        //type
        $this->add([
                'type' => Select::class,
                'name' => 'type',
                'options' => [
                    'label' => "Type de séance <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                    'label_options' => [ 'disable_html_escape' => true, ],
                    'value_options' => [
                        Seance::TYPE_SEANCE => "Séance de formation",
                        Seance::TYPE_VOLUME => "Volume horaire",
                    ],
                ],
                'attributes' => [
                    'id' => 'type',
                ],
            ]

        );

        /* SEANCE *********************************************************************/

        //jour
        $this->add([
            'type' => Date::class,
            'name' => 'jour',
            'options' => [
                'label' => "Jour de la formation <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'jour',
            ],
        ]);
        //debut
        $this->add([
            'type' => Time::class,
            'name' => 'debut',
            'options' => [
                'label' => "Début de la journée <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'H:i',
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);
        //fin
        $this->add([
            'type' => Time::class,
            'name' => 'fin',
            'options' => [
                'label' => "Fin de la journée <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'H:i'
            ],
            'attributes' => [
                'id' => 'fin',
            ],
        ]);

        /* VOLUME *********************************************************************/

        //number
        $this->add([
            'type' => Number::class,
            'name' => "volume",
            'options' => [
                'label' => "Volume horaire de la formation <span class='icon icon-asterisque' title='Champ obligatoire pour les volumes horaires' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'volume',
            ],
        ]);
        //jour
        $this->add([
            'type' => Date::class,
            'name' => 'volume_debut',
            'options' => [
                'label' => "Date d'ouverture du volume <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'volume_debut',
            ],
        ]);
        //jour
        $this->add([
            'type' => Date::class,
            'name' => 'volume_fin',
            'options' => [
                'label' => "Date de fermeture du volume <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'volume_fin',
            ],
        ]);
        //lieu
        $lieu = new SearchAndSelect('lieu-sas', ['label' => "Lieu :"]);
        $lieu
            ->setAutocompleteSource($this->url)
            ->setSelectionRequired()
            ->setAttributes([
                'id' => 'lieu-sas',
                'placeholder' => "Renseigner le nom du lieu ...",
            ]);
        $this->add($lieu);
        //lien
        $this->add([
            'type' => Text::class,
            'name' => "lien",
            'options' => [
                'label' => "Lien pour la sénace :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'creer',
                'type' => 'submit',
                'class' => 'btn btn-success',
            ],
        ]);
        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'type' => ['required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Une information obligatoire est manquante",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($context['type'] === Seance::TYPE_VOLUME) return ($context['volume'] !== '' AND $context['volume_debut'] AND $context['volume_fin'] !== '');
                            if($context['type'] === Seance::TYPE_SEANCE) return ($context['jour'] !== '' AND $context['debut'] !== '' AND $context['fin'] !== '');
                            return true;
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],],
            'jour' => ['required' => false,

            ],
            'debut' => ['required' => false,],
            'fin' => ['required' => false,],
            'volume' => ['required' => false,],
            'volume_debut' => ['required' => false,],
            'volume_fin' => ['required' => false,],
            'lieu-sas' => ['required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Le lieu est déjà utilisée à cette date",
                            ],
                            'callback' => function ($value, $context = []) {
                                if ($context['type'] === Seance::TYPE_VOLUME) return true;
                                $lieu = $this->getLieuService()->getLieu($context['lieu-sas']['id']);
                                $dateDebut =  DateTime::createFromFormat('d/m/Y H:i', $context['jour'].' '.$context['debut']);
                                $dateFin =  DateTime::createFromFormat('d/m/Y H:i', $context['jour'].' '.$context['fin']);
                                if ($lieu !== null) {
                                    $seances = $lieu->isUtilisee($dateDebut, $dateFin);
                                }
                                return (($lieu !== null) AND empty($seances));
                            },
                        ],
                    ]
//                    [
//                        'name' => LieuUtiliseValidator::class,
//                        'options' => [],
//                    ]
                ],
            ],
            'lien' => ['required' => false,],
        ]));
    }
}