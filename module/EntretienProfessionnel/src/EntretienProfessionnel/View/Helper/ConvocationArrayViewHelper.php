<?php

namespace EntretienProfessionnel\View\Helper;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Entity\Db\Agent;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Campagne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

/**
 * le tableau d'options peut recevoir un ensemble de booléen pour les droits afin de ne pas recalculer ou transmettre des valeurs différentes.
 * $options['droits']['afficher' => Boolean, 'renseigner' => Boolean, 'modifier' => Boolean, 'exporter' => Boolean, 'historiser' => Boolean, 'supprimer' => Boolean]
 */

class ConvocationArrayViewHelper extends AbstractHelper
{
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    /**
     * @param Agent[] $agents
     * @param Campagne $campagne
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $agents, Campagne $campagne, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgents($agents);
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgents($agents);
        $grades = $this->getAgentGradeService()->getAgentGradesByAgents($agents);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgents($agents);

        return $view->partial('convocation-array', ['agents' => $agents, 'campagne' => $campagne,
            'affectations' => $affectations, 'autorites' => $autorites, 'grades' => $grades, 'superieurs' => $superieurs,
            'options' => $options
        ]);
    }
}