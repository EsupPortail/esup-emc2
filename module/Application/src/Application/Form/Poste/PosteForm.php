<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Domaine;
use Application\Form\AutocompleteAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Application\Service\Immobilier\ImmobilierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class PosteForm extends Form  {
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use EntityManagerAwareTrait;
    use FonctionServiceAwareTrait;
    use ServiceLocatorAwareTrait;
    use StructureServiceAwareTrait;
    use ImmobilierServiceAwareTrait;

    use AutocompleteAwareTrait;

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
        // structure
        $this->add([
            'type' => Select::class,
            'name' => 'structure',
            'options' => [
                'label' => "Service/composante/direction d'affectation :",
                'empty_option'  => "Sélectionner une service ...",
                'value_options' => $this->getStructureService()->getStructuresAsOptions(),
            ],
            'attributes' => [
                'id'                => 'structure',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // localisation
        $this->add([
            'type' => Select::class,
            'name' => 'localisation',
            'options' => [
                'label' => "Localisation du poste :",
                'empty_option'  => "Sélectionner un bâtiment ...",
                'value_options' => $this->getImmobilierService()->getBatimentsAsOptions(),
            ],
            'attributes' => [
                'id'                => 'localisation',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // correspondance
        $this->add([
            'type' => Select::class,
            'name' => 'correspondance',
            'options' => [
                'label' => "Catégorie :",
                'empty_option'  => "Sélectionner une correspondance ...",
                'value_options' => $this->generateCorrespondanceSelectOptions(),
            ],
            'attributes' => [
                'id' => 'correspondance',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // rattachement
        $this->add([
            'type' => Select::class,
            'name' => 'rattachement',
            'options' => [
                'label' => "Rattachement hierarchique :",
                'empty_option'  => "Sélectionner un rattachement ...",
                'value_options' => $this->generateRattachementSelectOptions(),
            ],
            'attributes' => [
                'id' => 'rattachement',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // domaine
        $this->add([
            'type' => ObjectSelect::class,
            'name' => 'domaine',
            'options' => [
                'label' => "Domaine UNICAEN :",
                'empty_option' => "Sélectionner un domaine ...",
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
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // Fonction
        $this->add([
            'type' => Select::class,
            'name' => 'fonction',
            'options' => [
                'label' => "Fonction :",
                'empty_option'  => "Sélectionner une fonction ...",
                'value_options' => $this->getFonctionService()->getFonctionsAsOptions(),
            ],
            'attributes' => [
                'id' => 'fonction',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // liens exterieur vers fiche de poste
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien externe ver la fiche de poste :",
            ],
            'attributes' => [
                'id' => 'lien',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
        $correspondances = $this->getRessourceRhService()->getCorrespondances(true);
        $options = [];
        foreach($correspondances as $correspondance) {
            $options[$correspondance->getId()] = $correspondance->getLibelleCourt() . " - " . $correspondance->getLibelleLong();
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