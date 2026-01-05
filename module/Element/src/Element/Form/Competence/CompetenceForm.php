<?php

namespace Element\Form\Competence;

use Element\Entity\Db\CompetenceType;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class CompetenceForm extends Form {
    use CompetenceServiceAwareTrait;
    use CompetenceDisciplineServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "tinymce",
            ],
        ]);
        //type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de la compétence <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner le type de la compétence ...",
                'value_options' => $this->getCompetenceTypeService()->getCompetencesTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
                'class'             => 'show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //theme
        $this->add([
            'type' => Select::class,
            'name' => 'theme',
            'options' => [
                'label' => "Thème de compétence :",
                'empty_option' => "Sélectionner le thème de la compétence ...",
                'value_options' => $this->getCompetenceThemeService()->getCompetencesThemesAsOptions(),
            ],
            'attributes' => [
                'id' => 'theme',
                'class'             => 'show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => "Référentiel de compétence <span class='icon icon-info' title='Si aucun identifiant de renseigné, EMC2 ajoutera la compétence dans le référentiel interne (si il est bien déclarée).' data-bs-togle='tooltip' data-bs-html='true'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner un référentiel pour la compétence ...",
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //identifiant dans referentiel
        $this->add([
            'type' => Text::class,
            'name' => 'identifiant',
            'options' => [
                'label' => "Identifiant dans le référentiel source <span class='icon icon-info' title='Si aucun identifiant de renseigné, EMC2 donnera une valeur numérique à la compétence' data-bs-togle='tooltip' data-bs-html='true'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'identifiant',
            ],
        ]);
        //discipline
        $this->add([
            'type' => Select::class,
            'name' => 'discipline',
            'options' => [
                'label' => "Discipline de compétence :",
                'empty_option' => "Sélectionner la discipline de la compétence ...",
                'value_options' => $this->getCompetenceDisciplineService()->getCompetencesDisciplinesAsOptions(),
            ],
            'attributes' => [
                'id' => 'discipline',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        //submit
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
            'libelle'       => [ 'required' => true,  ],
            'description'   => [ 'required' => false,  ],
            'type'          => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Le champ discipline est obligatoire pour les compétences spécifiques.",
                        ],
                        'callback' => function ($value, $context = []) {
                            $type = $this->getCompetenceTypeService()->getCompetenceType($context['type']);
                            if ($type?->getCode() !== CompetenceType::CODE_SPECIFIQUE) return true;
                            return ($context['discipline'] !== null and trim($context['discipline']) !== '');
                        },
                    ],
                ]],
            ],
            'discipline'    => [
                'required' => false,
            ],
            'theme'         => [ 'required' => false, ],
            'referentiel'   => [ 'required' => false, ],
            'identifiant'   => [ 'required' => false, ],
        ]));
    }
}