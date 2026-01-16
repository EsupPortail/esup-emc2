<?php

namespace Carriere\Form\FamilleProfessionnelle;

use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class FamilleProfessionnelleForm extends Form
{
    use CorrespondanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    public function init(): void
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        // correspondance
        $this->add([
            'type' => Select::class,
            'name' => 'correspondance',
            'options' => [
                'label' => "Correspondance :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner une fiche métier ...',
                'value_options' => $this->getCorrespondanceService()->getCorrespondancesAsOptions(),
            ],
            'attributes' => [
                'id' => 'correspondance',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        // position
        $this->add([
            'type' => Number::class,
            'name' => 'position',
            'options' => [
                'label' => "Position dans la correspondance <span class='icon icon-info' title=\"Le positionnement doit être unique au sein d'une correspondance\"></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'position',
                'min' => 1,
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

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'correspondance' => ['required' => false,],
            'position' => ['required' => false,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "La position n'est pas disponible dans la correspondance",
                            ],
                            'callback' => function ($value, $context = []) {
                                $idCorrespondance = ($context['correspondance'] !== '') ? $context['correspondance'] : null;
                                $correspondance = $this->getCorrespondanceService()->getCorrespondance($idCorrespondance, false);
                                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByPositionnement($correspondance, $value);
                                if ($famille === null) return true;
                                if ($this->object === $famille) return true;
                                return false;
                            },
                        ],
                    ]
                ]
            ],
        ]));
    }

}
