<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceAwareTrait;
use DateInterval;
use DateTime;
//use UnicaenApp\Form\Element\Date;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class EntretienProfessionnelForm extends Form {
    use EntretienProfessionnelCampagneServiceAwareTrait;

    private $urlAgent;
    private $urlResponsable;

    /**
     * @param mixed $urlAgent
     * @return EntretienProfessionnelForm
     */
    public function setUrlAgent($urlAgent)
    {
        $this->urlAgent = $urlAgent;
        return $this;
    }

    /**
     * @param mixed $urlResponsable
     * @return EntretienProfessionnelForm
     */
    public function setUrlResponsable($urlResponsable)
    {
        $this->urlResponsable = $urlResponsable;
        return $this;
    }

    public function init()
    {
        /** Année Scolaire **/
        $date = new DateTime('now');
        $annee = ((int) $date->format('Y'));
        $anneeOpt = [];
        for ($i = $annee - 5; $i <= $annee + 5 ; $i++) {
            $text = $i . "/" . ($i + 1);
            $anneeOpt[$text] = $text;
        }

        //Responsable (connected user)
        $responsable = new SearchAndSelect('responsable', ['label' => "Responsable de l'entretien professionnel * :"]);
        $responsable
            ->setAutocompleteSource($this->urlResponsable)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'responsable',
                'placeholder' => "Nom du responsable de l'entretien professionnel ...",
            ]);
        $this->add($responsable);
        //Agent       (selection parmi liste des agents [du service])
        $agent = new SearchAndSelect('agent', ['label' => "Agent * :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
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
                'label' => 'Campagne * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une campagne ... ",
                'value_options' => $this->getEntretienProfessionnelCampagneService()->getEntretiensProfessionnelsCampagnesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'campagne',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);

        //Date        (initialisée à la date du jour)
        $this->add([
            'type' => \Zend\Form\Element\DateTime::class,
            'name' => 'date_entretien',
            'options' => [
                'label' => "Date de l'entretien* :",
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_entretien',
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
            'responsable'       => [ 'required' => true,  ],
            'agent'             => [ 'required' => true,  ],
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
                                $date = DateTime::createFromFormat('d/m/Y', $context['date_entretien']);
                                $res =  ($campagne->getDateDebut() <= $date AND $campagne->getDateFin() >= $date);
                                return $res;
                            },
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "L'entretien doit être conduit au minimum 15 jours après l'envoi de la convocation",
                            ],
                            'callback' => function ($value, $context = []) {
                                /** @var EntretienProfessionnel $entretien */
                                $maintenant = new DateTime();
                                $maintenant = $maintenant->add(new DateInterval('P14D'));
                                $date = DateTime::createFromFormat('d/m/Y', $context['date_entretien']);
                                $res =  ($maintenant <= $date);
                                return $res;
                            },
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
        ]));
    }
}
