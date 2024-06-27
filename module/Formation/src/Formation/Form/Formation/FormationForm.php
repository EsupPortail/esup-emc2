<?php

namespace Formation\Form\Formation;

use Application\Form\HasDescription\HasDescriptionFieldset;
use Formation\Entity\Db\Formation;
use Formation\Service\ActionType\ActionTypeServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FormationForm extends Form
{
    use FormationGroupeServiceAwareTrait;
    use ActionTypeServiceAwareTrait;

    public function init(): void
    {

        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //groupe
        $this->add([
            'name' => 'groupe',
            'type' => Select::class,
            'options' => [
                'label' => 'Groupe de la formation : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => 'Sélectionner un groupe ...',
                'value_options' => $this->getFormationGroupeService()->getFormationsGroupesAsOption(),
            ],
            'attributes' => [
                'class' => 'description form-control show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //description
        $this->add([
            'name' => 'HasDescription',
            'type' => HasDescriptionFieldset::class,
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        //type
        $this->add([
            'name' => 'type',
            'type' => Select::class,
            'options' => [
                'label' => "Modalité de présence/formation <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [ 'class' => 'control-label', ],
                'empty_option' => 'Sélectionner un mode ...',
                'value_options' => Formation::TYPES,
            ],
            'attributes' => [
                'class' => 'description form-control show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //type
        $this->add([
            'name' => 'actiontype',
            'type' => Select::class,
            'options' => [
                'label' => "Type d'action :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [ 'class' => 'control-label', ],
                'empty_option' => 'Sélectionner un type ...',
                'value_options' => $this->getActionTypeService()->getActionsTypesAsOptions(),
            ],
            'attributes' => [
                'class' => 'form-control show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //objectifs
        $this->add([
            'type' => Textarea::class,
            'name' => 'objectifs',
            'options' => [
                'label' => "Objectif :",
            ],
            'attributes' => [
                'id' => 'objectifs',
                'class' => 'type2 form-control',
            ],
        ]);
        //programme
        $this->add([
            'type' => Textarea::class,
            'name' => 'programme',
            'options' => [
                'label' => "Programme :",
            ],
            'attributes' => [
                'id' => 'programme',
                'class' => 'type2 form-control',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'prerequis',
            'options' => [
                'label' => "Pré-requis :",
            ],
            'attributes' => [
                'id' => 'prerequis',
                'class' => 'type2 form-control',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'public',
            'options' => [
                'label' => "Public visé :",
            ],
            'attributes' => [
                'id' => 'public',
                'class' => 'type2 form-control',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => "complement",
            'options' => [
                'label' => "Complément <span class='icon icon-information text-info' title='Figurera sur les convocations des sessions associées' data-bs-toggle='tooltip' data-bs-html='true'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'complement',
                'class' => 'type2 form-control',
            ],
        ]);

        //lien
        $this->add([
            'type' => Text::class,
            'name' => 'lien',
            'options' => [
                'label' => "Lien :",
            ],
            'attributes' => [
                'id' => 'lien',
            ],
        ]);

        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'affichage',
            'options' => [
                'label' => "Affichage dans le 'plan de formation à venir'  <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'affichage',
            ],
        ]);
        //rattachement
        $this->add([
            'type' => Select::class,
            'name' => 'rattachement',
            'options' => [
                'label' => "Action de formation rattachée  :",
                'empty_option' => "Aucun rattachement particulier",
                'value_options' => [
                    Formation::RATTACHEMENT_PREVENTION => 'service de prévention',
                    Formation::RATTACHEMENT_BIBLIOTHEQUE => 'service de documentation',
                ],
            ],
            'attributes' => [
                'id' => 'rattachement',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => ['required' => true,],
            'groupe' => ['required' => false,],
            'description' => ['required' => false,],
            'lien' => ['required' => false,],
            'affichage' => ['required' => true,],
            'rattachement' => ['required' => false,],
            'type' => ['required' => true,],
            'actiontype' => ['required' => false,],
            'objectifs' => ['required' => false,],
            'programme' => ['required' => false,],
            'prerequis' => ['required' => false,],
            'public' => ['required' => false,],
            'complement' => ['required' => false,],
        ]));
    }
}