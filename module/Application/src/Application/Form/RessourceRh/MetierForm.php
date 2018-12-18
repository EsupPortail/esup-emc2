<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MetierFamille;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class MetierForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        // Status
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'famille',
            'options' => [
                'label' => "Famille de métier* :",
                'empty_option' => "Sélectionner une famille de métier ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => MetierFamille::class,
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
                'id' => 'famille',
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
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer le métier',
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