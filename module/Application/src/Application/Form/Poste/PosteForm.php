<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Affectation;
use Application\Entity\Db\Domaine;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class PosteForm extends Form {
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use EntityManagerAwareTrait;

    public function init()
    {
        // numero_poste
        $this->add([
            'type' => Text::class,
            'name' => 'numero_poste',
            'options' => [
                'label' => "Numero de poste :",
            ],
            'attributes' => [
                'id' => 'numero_poste',
            ],
        ]);
        // affectation
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

        // localisation
        $this->add([
            'type' => Text::class,
            'name' => 'localisation',
            'options' => [
                'label' => "Localisation :",
            ],
            'attributes' => [
                'id' => 'localisation',
            ],
        ]);
        // correspondance
        $this->add([
            'type' => Select::class,
            'name' => 'correspondance',
            'options' => [
                'label' => "Correspondance :",
                'value_options' => $this->generateCorrespondanceSelectOptions(),
            ],
            'attributes' => [
                'id' => 'correspondance',
            ],
        ]);

        // rattachement
        $this->add([
            'type' => Select::class,
            'name' => 'rattachement',
            'options' => [
                'label' => "Rattachement hierarchique :",
                'value_options' => $this->generateRattachementSelectOptions(),
            ],
            'attributes' => [
                'id' => 'agent',
            ],
        ]);

        // domaine
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine UNICAEN :",
                'empty_option' => "Sélectionner un domaine",
                'object_manager' => $this->getEntityManager(),
                'target_class' => Domaine::class,
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
                'id' => 'domaine',
            ],
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer le poste',
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

    private function generateCorrespondanceSelectOptions()
    {
        $correspondances = $this->getRessourceRhService()->getCorrespondances();
        $options = [];
        $options[0] = "Sélectionner une correspondance ...";
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = $correspondance->__toString();
        }
        return $options;
    }

    private function generateRattachementSelectOptions()
    {
        $agents = $this->getAgentService()->getAgents();
        $options = [];
        $options[0] = "Sélectionner un agent ...";
        foreach($agents as $agent) {
            $options[$agent->getId()] = $agent->getDenomination();
        }
        return $options;
    }

}