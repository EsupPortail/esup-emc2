<?php

namespace Application\Form\FicheMetierType;

use Application\Entity\Db\Activite;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Form;

class ActiviteExistanteForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'activite',
            'options' => [
                'label' => "Activité :",
                'empty_option' => "Sélectionner une activité ...",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Activite::class,
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
                'id' => 'activite',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Ajouter',
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