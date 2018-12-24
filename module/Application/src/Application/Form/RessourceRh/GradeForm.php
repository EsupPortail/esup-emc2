<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Corps;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class GradeForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        // Status
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'corps',
            'options' => [
                'label' => "Corps* :",
                'empty_option' => "Sélectionner un corps ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Corps::class,
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
                'id' => 'corps',
            ],
        ]);
        // libelle
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
        // rang
        $this->add([
            'type' => Text::class,
            'name' => 'rang',
            'options' => [
                'label' => "Rang :",
            ],
            'attributes' => [
                'id' => 'rang',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer le grade',
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