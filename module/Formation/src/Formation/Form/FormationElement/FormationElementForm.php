<?php

namespace Formation\Form\FormationElement;

use DateTime;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FormationElementForm extends Form
{
    use FormationServiceAwareTrait;

    public function init(): void
    {
        //formation
        $this->add([
            'name' => 'formation',
            'type' => Select::class,
            'options' => [
                'label' => 'Formation * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une formation ... ",
                'value_options' => $this->getFormationService()->getFormationsGroupesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'formation',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ]
        ]);
        //niveau
        $this->add([
            'name' => 'niveau',
            'type' => Select::class,
            'options' => [
                'label' => 'Niveau * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner un niveau ... ",
                'value_options' => [
                    'Débutant' => "Débutant",
                    'Intermédiaire' => "Intermédiaire",
                    'Expert' => "Expert",
                ],
            ],
            'attributes' => [
                'id' => 'niveau',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ]
        ]);
        //Année
        $this->add([
            'type' => Select::class,
            'name' => 'annee',
            'options' => [
                'label' => "Année de la formation :",
                'empty_value' => 'Sélectionner une année ...',
                'value_options' => $this->getAnneesScolaires(((int)(new DateTime())->format('Y')) - 20, ((int)(new DateTime())->format('Y')) + 5),
            ],
            'attributes' => [
                'id' => 'annee',
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
                'class' => 'btn btn-success',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'formation' => ['required' => true,],
            'niveau' => ['required' => true,],
            'annee' => ['required' => false,],
        ]));
    }

    /**
     * @param int $debut
     * @param int $fin
     * @return array
     */
    public function getAnneesScolaires(int $debut, int $fin): array
    {
        $result = [];
        for ($annee = $debut; $annee <= $fin; $annee++) {
            $result[] = $annee . '/' . ($annee + 1);
        }
        return $result;
    }
}