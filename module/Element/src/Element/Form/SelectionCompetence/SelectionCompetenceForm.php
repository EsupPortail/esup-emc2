<?php

namespace Element\Form\SelectionCompetence;

use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\CompetenceType;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionCompetenceForm extends Form {
    use CompetenceServiceAwareTrait;

    private ?CompetenceType $type = null;
    private ?Collection $collection = null;

    public function getCollection(): ?Collection
    {
        return $this->collection;
    }

    public function setCollection(?Collection $collection): void
    {
        $this->collection = $collection;
    }



    public function init(): void
    {

        //select multiple avec groupe
        $this->add([
            'type' => Select::class,
            'name' => 'competences',
            'options' => [
                'label' => "Compétences associées :",
                'value_options' => $this->getCompetenceService()->getCompetencesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'competences',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'enregistrer',
            'options' => [
                'label' => 'Valider',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'competences'               => [ 'required' => false,  ],
        ]));
    }

    public function reinit(CompetenceType $type): void
    {
        $this->type = $type;
        $this->hydrator->setType($type);
        $this->get('competences')->setValueOptions($this->getCompetenceService()->getCompetencesAsGroupOptions($type));
    }
}