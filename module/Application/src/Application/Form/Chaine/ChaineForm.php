<?php

namespace Application\Form\Chaine;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use RuntimeException;
use UnicaenApp\Form\Element\SearchAndSelect;

class ChaineForm extends  Form {

    private string $urlAgent;

    public function getUrlAgent(): string
    {
        return $this->urlAgent;
    }

    public function setUrlAgent(string $urlAgent): void
    {
        $this->urlAgent = $urlAgent;
    }



    public function init(): void
    {
        //agent *
        $agent = new SearchAndSelect('agent', ['label' => "Agent.e :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'Agent',
                'placeholder' => "Nom du l'agent·e ...",
            ]);
        $this->add($agent);
        //responsable *
        switch ($this->object) {
            case ($this->object instanceof AgentSuperieur) :
                $label = "Supérieur·e<span class='icon icon-obligatoire'></span> :";
                $placeholder = "Nom du Supérieur·e ...";
                break;
            case ($this->object instanceof AgentAutorite) :
                $label = "Autorité<span class='icon icon-obligatoire'></span> :";
                $placeholder = "Nom de l'autorité ...";
            default :
                throw new RuntimeException("ChaineForm::init() : objet de type inconnu [" . get_class($this->getObject()) . "]");
        }
        $responsable = new SearchAndSelect('responsable', ['label' => $label]);
        $responsable
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'Responsable',
                'placeholder' => $placeholder,
            ]);
        $this->add($responsable);
        //datedebut *
        $this->add([
            'type' => Date::class,
            'name' => 'date_debut',
            'options' => [
                'label' => "Date de début <span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'date_debut',
            ],
        ]);
        $this->add([
            'type' => Date::class,
            'name' => 'date_fin',
            'options' => [
                'label' => "Date de fin :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'date_fin',
            ],
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer ',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //input filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'agent' => ['required' => true,],
            'responsable' => ['required' => true,],
            'date_debut' => ['required' => true,],
            'date_fin' => ['required' => false,],
        ]));
    }
}