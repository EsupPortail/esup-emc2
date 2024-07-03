<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Time;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class EntretienProfessionnelForm extends Form {
    use CampagneServiceAwareTrait;

    private $urlAgent;

    private array $superieurs = [];
    public function setSuperieurs(array $superieurs) { $this->superieurs = $superieurs; }
    public function superieursAsOptions() : array
    {
        $options = [];
        foreach ($this->superieurs as $superieur) $options[$superieur->getId()] = $superieur->getDenomination();
        return $options;
    }


    /**
     * @param mixed $urlAgent
     * @return EntretienProfessionnelForm
     */
    public function setUrlAgent($urlAgent) : EntretienProfessionnelForm
    {
        $this->urlAgent = $urlAgent;
        return $this;
    }

    public function init(): void
    {
         $this->add([
            'type' => Select::class,
            'name' => 'responsable',
            'options' => [
                'label' => "Responsable de l'entretien <span class='icon icon-obligatoire' title='Champ obligatoire' ></span> <span class='icon icon-information' title='La liste ci-dessous correspond aux supérieur·es hiérarchique·s direct·es déclaré·es' ></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner un·e responsable pour l'entretien",
                'value_options' => $this->superieursAsOptions(),
            ],
            'attributes' => [
                'id' => 'responsable',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        //Agent
        $agent = new SearchAndSelect('agent', ['label' => "Agent <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'agent',
                'placeholder' => "Nom de l'agent ...",
            ]);
        $this->add($agent);
        //CAMPAGNE (SELECT)
        $this->add([
            'name' => 'campagne',
            'type' => Select::class,
            'options' => [
                'label' => "Campagne  <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> : ",
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner une campagne ... ",
                'value_options' => $this->getCampagneService()->getCampagnesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'campagne',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);

        //Date (initialisée à la date du jour)
        $this->add([
            'type' => Date::class,
            'name' => 'date_entretien',
            'options' => [
                'label' => "Date de l'entretien <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
//                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_entretien',
            ],
        ]);

        //Heure (initialisée à la date du jour)
        $this->add([
            'type' => Time::class,
            'name' => 'heure_entretien',
            'options' => [
                'label' => "Heure de l'entretien <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'format' => 'H:i',
            ],
            'attributes' => [
                'id' => 'heure_entretien',
            ],
        ]);

        //Lieu
        $this->add([
            'type' => Text::class,
            'name' => 'lieu_entretien',
            'options' => [
                'label' => "Lieu de l'entretien <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'lieu_entretien',
            ],
        ]);

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

        $this->setInputFilter((new Factory())->createInputFilter([
            'responsable'       => [
                'required' => true,
//                'validators' => [
//                    [
//                        'name' => Callback::class,
//                        'options' => [
//                            'messages' => [
//                                Callback::INVALID_VALUE => "Veuillez sélectionner un responsable dans la liste déroulante.",
//                            ],
//                            'callback' => function ($value, $context = []) {
//                                $hasResponsable = (isset($context['responsable']) AND isset($context['responsable']['id']) AND trim($context['responsable']['id']) !== '');
//                                return $hasResponsable;
//                            },
//                        ],
//                    ],
//                ],
            ],
            'agent'             => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Veuillez sélectionnez un responsable dans la liste déroulante.",
                            ],
                            'callback' => function ($value, $context = []) {
                                $hasResponsable = (isset($context['agent']) AND isset($context['agent']['id']) AND trim($context['agent']['id']) !== '');
                                return $hasResponsable;
                            },
                        ],
                    ],
                ],
            ],
            'campagne'          => [ 'required' => true,  ],
            'date_entretien'    => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "L'entretien doit être conduit durant la campagne",
                            ],
                            'callback' => function ($value, $context = []) {
                                /** @var EntretienProfessionnel $entretien */
                                $entretien = $this->getObject();
                                $campagne = $entretien->getCampagne();
                                $date = DateTime::createFromFormat('Y-m-d', $context['date_entretien']);
                                $res =  ($campagne->getDateDebut() <= $date AND $campagne->getDateFin() >= $date);
                                return $res;
                            },
                        ],
                    ],
//                    [
//                        'name' => Callback::class,
//                        'options' => [
//                            'messages' => [
//                                Callback::INVALID_VALUE => "L'entretien doit être conduit au minimum 15 jours après l'envoi de la convocation",
//                            ],
//                            'callback' => function ($value, $context = []) {
//                                /** @var EntretienProfessionnel $entretien */
//                                $maintenant = new DateTime();
//                                $maintenant = $maintenant->add(new DateInterval('P14D'));
//                                $date = DateTime::createFromFormat('Y-m-d', $context['date_entretien']);
//                                $res =  ($maintenant <= $date);
//                                return $res;
//                            },
//                        ],
//                    ],
                ],
            ],
            'heure_entretien'          => [ 'required' => true,  ],
            'lieu_entretien'           => [ 'required' => true,  ],
        ]));
    }
}
