<?php

namespace Agent\View\Helper;

use Agent\Entity\Db\Agent;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/**
 * La raison derrière ce ViewHelper est de simplifier le plus possible l'écriture des actions qui présenteront ces
 * actions, et ainsi de pouvoir "oublier" le besoin de gérer les paramètres pour l'activation/désactivation des onglets.
 */
class AgentOngletViewHelper extends AbstractHelper
{
    use CampagneServiceAwareTrait;
    use UserServiceAwareTrait;

    private array $parametres = [];

    public function getParametres(): array
    {
        return $this->parametres;
    }

    public function setParametres(array $parametres): void
    {
        $this->parametres = $parametres;
    }


    public function __invoke(Agent $agent, string $current, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('agent-onglet-vh', ['agent' => $agent, 'campagnesActives' => $this->getCampagneService()->getCampagnesActives(), 'connectedUser' => $this->getUserService()->getConnectedUser(), 'connectedRole' => $this->getUserService()->getConnectedRole(), 'current' => $current, 'parametres' => $this->getParametres(), 'options' => $options]);
    }
}
