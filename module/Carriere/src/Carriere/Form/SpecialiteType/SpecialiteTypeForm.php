<?php

namespace Carriere\Form\SpecialiteType;

use Carriere\Entity\Db\CorrespondanceType;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class SpecialiteTypeForm extends Form
{
    use CorrespondanceTypeServiceAwareTrait;

    public function init(): void
    {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code <span class='icon icon-obligatoire' title='Champ obligatoire et unique'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        //Description
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
        //d_ouverture
        $this->add([
            'type' => Date::class,
            'name' => 'date_ouverture',
            'options' => [
                'label' => "Date d'ouverture <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'code'   => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code est déjà utilisé",
                        ],
                        'callback' => function ($value, $context = []) {
                            /** @var CorrespondanceType $object */
                            $object = $this->getObject();
                            if ($object AND $object->getCode() == trim($value)) return true;
                            return ($this->getCorrespondanceTypeService()->getCorrespondanceTypeByCode(trim($value)) === null);
                        },
                    ],
                ]],
            ],
            'libelle_court'   => [ 'required' => true, ],
            'libelle_long'   => [ 'required' => true, ],
            'description'     => [ 'required' => false, ],
            'date_ouverture'     => [ 'required' => true, ],
            'date_fermeture'     => [ 'required' => false, ],
        ]));
    }
}
