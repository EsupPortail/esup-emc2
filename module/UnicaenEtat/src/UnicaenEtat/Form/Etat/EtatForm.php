<?php

namespace UnicaenEtat\Form\Etat;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenPrivilege\Entity\Db\Privilege;
use Zend\Form\Element\Button;
use Zend\Form\Element\Color;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class EtatForm extends Form
{
    use EtatTypeServiceAwareTrait;
    use EntityManagerAwareTrait;

    /** @var string */
    private $oldCode;

    /**
     * @param string $oldCode
     * @return EtatForm
     */
    public function setOldCode(string $oldCode): EtatForm
    {
        $this->oldCode = $oldCode;
        return $this;
    }

    public function init()
    {
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code* :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        $this->add([
            'name' => 'old-code',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type d'état* :",
                'empty_option' => 'Sélectionner un type ...',
                'value_options' => $this->getEtatTypeService()->getEtatTypesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //icone
        $this->add([
            'type' => Text::class,
            'name' => 'icone',
            'options' => [
                'label' => "Icone :",
            ],
            'attributes' => [
                'id' => 'icone',
            ],
        ]);
        //couleur
        $this->add([
            'type' => Color::class,
            'name' => 'couleur',
            'options' => [
                'label' => "Couleur :",
            ],
            'attributes' => [
                'id' => 'couleur',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
            'code' => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $context['old-code']) return true;
                            return ($this->getEntityManager()->getRepository(Privilege::class)->findOneBy(['code'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-code' => ['required' => false, ],
            'libelle'  => ['required' => true, ],
            'couleur'  => ['required' => false, ],
            'icone'    => ['required' => false, ],
        ]));
    }
}