<?php

namespace Application\Form\Validation;

use Application\Service\Validation\ValidationValeurServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ValidationForm extends Form
{
    use ValidationValeurServiceAwareTrait;

    public function init()
    {
        //type (non editable)
        $this->add([
            'type' => Text::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de validation :",
            ],
            'attributes' => [
                'id' => 'type',
                'readonly' => true,
            ],
        ]);
        //object id (non editable)
        $this->add([
            'type' => Text::class,
            'name' => 'object_id',
            'options' => [
                'label' => "Identifiant de l'objet concerné :",
            ],
            'attributes' => [
                'id' => 'object_id',
                'readonly' => true,
            ],
        ]);
        //valeur (select)
        $this->add([
            'name' => 'valeur',
            'type' => Select::class,
            'options' => [
                'label' => 'Valeur* : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'value_options' => $this->getValidationValeurService()->getValidationValeurAsOptions(),
            ],
            'attributes' => [
                'id' => 'valeur',
            ]
        ]);
        //commentaire (textarea)
        $this->add([
            'type' => Textarea::class,
            'name' => 'commentaire',
            'options' => [
                'label' => "Commentaire :",
            ],
            'attributes' => [
                'name' => 'commentaire',
                'id' => 'commentaire',
                'class' => "type2",
            ],
        ]);
        //submit (button)
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
                'class' => 'btn btn-primary action',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'valeur'        => [ 'required' => true, ],
            'commentaire'   => [ 'required' => false, ],
            'type'          => [ 'required' => false, ],
            'object_id'     => [ 'required' => false, ],
        ]));
    }
}