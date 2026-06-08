<?php

namespace Carriere\Form\Specialite;

use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\CorrespondanceType;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class SpecialiteForm extends Form
{
    use CorrespondanceTypeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;

    public function init(): void
    {
        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de la spécialité <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => $this->getCorrespondanceTypeService()->getCorrespondancesTypesAsOptions(),
                'empty_option' => "Choisissez un type ",
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code <span class='icon icon-obligatoire' title='Champ obligatoire et unique'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //libelleCourt
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libellé court <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle_court',
            ],
        ]);
        //libelleLong
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_long',
            'options' => [
                'label' => "Libellé long <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        //d_ouverture
        $this->add([
            'type' => Date::class,
            'name' => 'date_ouverture',
            'options' => [
                'label' => "Date d'ouverture <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
//                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_ouverture',
                'placeholder' => 'jj/mm/aaaaa',
            ],
        ]);
        //d_fermeture
        $this->add([
            'type' => Date::class,
            'name' => 'date_fermeture',
            'options' => [
                'label' => "Date de fermeture :",
                'label_options' => ['disable_html_escape' => true,],
//                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date_fermeture',
                'placeholder' => 'jj/mm/aaaaa',
            ],
        ]);
        //bouton
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

        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'type' => ['required' => true,],
            'code' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code est déjà utilisé",
                        ],
                        'callback' => function ($value, $context = []) {
                            /** @var CorrespondanceType $type */
                            $type = $this->getCorrespondanceTypeService()->getCorrespondanceType($context['type']);
                            /** @var Correspondance $object */
                            $object = $this->getObject();
                            if ($object and $object->getCategorie() == trim($value)) return true;
                            return ($this->getCorrespondanceService()->getCorrespondanceByTypeAndCode($type?->getCode(), trim($value)) === null);
                        },
                    ],
                ]],
            ],
            'libelle_court' => ['required' => true,],
            'libelle_long' => ['required' => true,],
            'date_ouverture' => ['required' => true,],
            'date_fermeture' => ['required' => false,],
        ]));
    }
}
