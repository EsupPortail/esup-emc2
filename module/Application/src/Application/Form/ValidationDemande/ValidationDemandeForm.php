<?php
    
namespace Application\Form\ValidationDemande;

use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ValidationDemandeForm extends Form {
    use UserServiceAwareTrait;

    private $cibles;

    /**
     * @param array $cibles
     * @return ValidationDemandeForm
     */
    public function setCibles($cibles)
    {
        $this->cibles = $cibles;
        return $this;
    }

    public function init()
    {
        $this->add([
                'name' => 'cible',
                'type' => Select::class,
                'options' => [
                    'label' => 'Cible de la validation* : ',
                    'label_attributes' => [
                        'class' => 'control-label',
                    ],
//                    'empty_option' => 'Sélectionner une cible pour la validation ...',
                    'value_options' => $this->cibles,
                ],
                'attributes' => [
                    'class' => 'selectpicker form-control',
                    'style' => 'height:300px;',
                    'multiple' => 'multiple',
                    'data-live-search' => true,
                ]
        ]);
        $this->add([
            'name' => 'validateur',
            'type' => Select::class,
            'options' => [
                'label' => 'Validateur* : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un validateur ...',
                'value_options' => $this->getUserService()->getUtilisateursByRoleIdAsOptions('Validateur'),
            ],
            'attributes' => [
                'class' => 'selectpicker form-control',
                'style' => 'height:300px;',
                'data-live-search' => true,
            ]
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
            'cible'         => [ 'required' => true, ],
            'validateur'    => [ 'required' => true, ],
        ]));
    }
}