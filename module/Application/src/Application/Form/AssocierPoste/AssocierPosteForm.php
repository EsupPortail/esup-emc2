<?php

namespace Application\Form\AssocierPoste;

use Application\Entity\Db\Poste;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Form;

class AssocierPosteForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        //Selection obligatoire AFFECTATION
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'poste',
            'options' => [
                'label' => "Poste :",
                'empty_option' => "Sélectionner un poste",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Poste::class,
                'property' => 'numeroPoste',
                'find_method' => [
                    'name' => 'findBy',
                    'params' => [
                        'criteria' => [],
                        'orderBy' => ['numeroPoste' => 'ASC'],
                    ],
                ],
                'disable_inarray_validator' => true,
            ],
            'attributes' => [
                'id' => 'poste',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer le poste',
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