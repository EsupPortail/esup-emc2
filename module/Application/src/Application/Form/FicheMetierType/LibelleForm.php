<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\Metier;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Form;

class LibelleForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        // libelle
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'metier',
            'options' => [
                'label' => "Service/composante/direction d'affectation :",
                'empty_option' => "Sélectionner un metier ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Metier::class,
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
                'id' => 'metier',
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
    }
}