<?php

namespace Application\Form\MaitriseNiveau;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class MaitriseNiveauForm extends Form {
    use MaitriseNiveauServiceAwareTrait;

    /** @var string type */
    private $type;

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return MaitriseNiveauForm
     */
    public function setType(?string $type): MaitriseNiveauForm
    {
        $this->type = $type;
        return $this;
    }


    public function init()
    {
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
        //niveau
        $this->add([
            'type' => Number::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau * :",
            ],
            'attributes' => [
                'id' => 'niveau',
            ],
        ]);
        $this->add([
            'name' => 'old-niveau',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        // description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ]
        ]);
        //type
        $this->add([
            'type' => Hidden::class,
            'name' => 'type',
            'value' => $this->type,
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer' ,
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'            => [ 'required' => true, ],
            'type'               => [ 'required' => true, ],
            'niveau'             => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce niveau est déjà utilisé",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value ==  $context['old-niveau']) return true;
                            return ($this->getMaitriseNiveauService()->getMaitriseNiveauByNiveau($value) == null);
                        },
                    ],
                ]],
            ],
            'description'        => [ 'required' => false, ],
        ]));
    }
}