<?php

namespace EntretienProfessionnel\View\Helper;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use EntretienProfessionnel\Assertion\EntretienProfessionnelAssertion;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['affichages']['campagne' => Boolean, 'structure' => Boolean, 'agent' => Boolean, 'responsable' => Boolean, 'date' => Boolean, 'etat' => Boolean, 'action' => Boolean]
 * $options['droits']['afficher' => Boolean, 'renseigner' => Boolean, 'modifier' => Boolean, 'exporter' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */
class EntretienProfessionnelArrayViewHelper extends AbstractHelper
{
    use AgentAffectationServiceAwareTrait;

    protected array $entretiens;
    protected array $options;

    public ?EntretienProfessionnelAssertion $assertion = null;

    public function setAssertion(?EntretienProfessionnelAssertion $assertion): void
    {
        $this->assertion = $assertion;
    }


    public function __invoke(array $entretiens, array $options = [])
    {
        $this->entretiens = $entretiens;
        $this->options = $options;


        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByEntretiensProfessionnels($entretiens);

        return $view->partial('entretien-professionnel-array', [
            'assertion' => $this->assertion,
            'entretiens' => $this->entretiens,
            'options' => $this->options,
            'affectations' => $affectations,
        ]);
    }

    public function __toString()
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('entretien-professionnel-array', ['assertion' => $this->assertion, 'entretiens' => $this->entretiens, 'options' => $this->options]);
    }
}