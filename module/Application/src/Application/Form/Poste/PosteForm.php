<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Domaine;
use Application\Entity\Db\Fonction;
use Application\Entity\Db\Structure;
use Application\Form\AutocompleteAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DoctrineModule\Form\Element\ObjectSelect;
use UnicaenApp\Form\Element\SearchAndSelect;
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
                'value_options' => $this->getStructureService()->getStructuresAsOptions(),
            ],
            'attributes' => [
                'id' => 'structure',
            ],
        ]);

        // localisation
        $sas = new SearchAndSelect('localisation');
        $sas->setLabel('Localisation du poste');
        $sas->setAttribute('placeholder','Recherchez un batiment');
        $sas->setAttribute('class', 'individu-finder');
        $sas->setLabelOption('disable_html_escape', false);

        $sas->setAutocompleteSource($this->getAutocomplete());
        $this->add($sas);

        // correspondance
        $this->add([
            'type' => Select::class,
            'name' => 'correspondance',
            'options' => [
                'label' => "Catégorie :",
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
                'id' => 'rattachement',
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

        // Fonction
        $this->add([
            'type' => Select::class,
            'name' => 'fonction',
            'options' => [
                'label' => "Fonction :",
                'value_options' => $this->getFonctionService()->getFonctionsAsOption(),
            ],
            'attributes' => [
                'id' => 'fonction',
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