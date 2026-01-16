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
            'name' => 'specialite',
            'options' => [
                'label' => "Spécialité <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner une spécialité',
                'value_options' => $this->getCorrespondanceService()->getCorrespondancesAsOptions(),
            ],
            'attributes' => [
                'id' => 'specialite',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        // position
        $this->add([
            'type' => Number::class,
            'name' => 'position',
            'options' => [
                'label' => "Position dans la spécialité <span class='icon icon-info' data-bs-toggle='tooltip' data-bs-html='true' title='Le positionnement dans la correspondance sert uniquement pour la fonctionnalité \"code fonction\" quand elle est activée. Attention, le positionnement doit être unique dans la spécialité'></span> :",
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
            'specialite' => ['required' => true,],
            'position' => ['required' => false,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "La position n'est pas disponible dans la specialite",
                            ],
                            'callback' => function ($value, $context = []) {
                                $idCorrespondance = ($context['specialite'] !== '') ? $context['specialite'] : null;
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
