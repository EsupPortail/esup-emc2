<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use UnicaenApp\Form\Element\Date;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class EntretienProfessionnelForm extends Form {
    use RoleServiceAwareTrait;
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;

    public function init()
    {
        /** Récupération des gestionnaires **/
        $roleGestionnaire = $this->getRoleService()->getRoleByCode(Role::GESTIONNAIRE);
        $gestionnaires    = $this->getUserService()->getUtilisateursByRole($roleGestionnaire);
        $gestionnairesOpt = [];
        $gestionnairesOpt[ null ] = 'Sélectionnaire un gestionnaire de structure ... ';
        foreach ($gestionnaires as $gestionnaire) {
            $gestionnairesOpt[$gestionnaire->getId()] = $gestionnaire->getDisplayName();
        }

        /** Récupétation des personnels **/
        $agents = $this->getAgentService()->getAgents();
        $personnelsOpt = [];
        $personnelsOpt[ null ] = 'Sélectionnaire un personnel ... ';
        foreach ($agents as $agent) {
            $personnelsOpt[$agent->getId()] = $agent->getDenomination();
        }

        /** Année Scolaire **/
        $date = new DateTime('now');
        $annee = ((int) $date->format('Y'));
        $anneeOpt = [];
        for ($i = $annee - 5; $i <= $annee + 5 ; $i++) {
            $text = $i . "/" . ($i + 1);
            $anneeOpt[$text] = $text;
        }

        //Responsable (connected user)
        $this->add([
            'type' => Select::class,
            'name' => 'responsable',
            'options' => [
                'label' => "Responsable de l'entretien* :",
                'value_options' => $gestionnairesOpt,
            ],
            'attributes' => [
                'id' => 'responsable',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //Agent       (selection parmi liste des agents [du service])
        $this->add([
            'type' => Select::class,
            'name' => 'agent',
            'options' => [
                'label' => "Agent passant l'entretien* :",
                'value_options' => $personnelsOpt,
            ],
            'attributes' => [
                'id' => 'agent',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //Annee       (initialisée à l'annee scolaire en cours)
        $this->add([
            'type' => Select::class,
            'name' => 'annee',
            'options' => [
                'label' => "Année scolaire de l'entretien* :",
                'value_options' => $anneeOpt,
            ],
            'attributes' => [
                'id' => 'annee',
            ],
        ]);

        //Date        (initialisée à la date du jour)
        $this->add([
            'type' => Date::class,
            'name' => 'date_entretien',
            'options' => [
                'label' => "Date de l'entretien* :",
            ],
            'attributes' => [
                'id' => 'date_entretien',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'responsable'       => [ 'required' => true,  ],
            'agent'             => [ 'required' => true,  ],
            'annee'             => [ 'required' => true,  ],
            'date_entretien'    => [ 'required' => true,  ],
        ]));
    }
}