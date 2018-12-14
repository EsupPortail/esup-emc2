<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\Affectation;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class FicheMetierCreationForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        //Champs texte obligatoire LIBELLE
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //Selection obligatoire AFFECTATION
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'affectation',
            'options' => [
                'label' => "Service/composante/direction d'affectation :",
                'empty_option' => "Sélectionner une affectation",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Affectation::class,
                'property' => 'libelle',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['libelle' => 'ASC'],
                    ],
                ],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id' => 'affectation',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Créer la fiche métier',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}