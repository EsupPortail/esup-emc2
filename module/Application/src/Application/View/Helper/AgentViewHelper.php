<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Zend\View\Helper\AbstractHelper;

class AgentViewHelper extends AbstractHelper
{

    /** @var Agent */
    protected $agent;

    /**
     * @param Agent $agent
     * @return $this
     */
    public function __invoke($agent = null)
    {
        $this->agent = $agent;
        return $this;
    }

    public function __call($name, $arguments)
    {
        $attr = call_user_func_array([$this->agent, $name], $arguments);
        return $this;
    }

    /**
     * @param  Agent $agent
     * @return string
     */
    public function render($agent)
    {
        $texte  = '';
        $texte .= '<h3>Présentation de l\'agent </h3>';

        $texte .= '<dl class="dl-horizontal">';
        $texte .= '<dt> Prénom </dt>';
        $texte .= '<dd> '. $agent->getPrenom() . '</dd>';
        $texte .= '<dt> Nom </dt>';
        $texte .= '<dd> '. $agent->getNomUsuel() .'</dd>';
        $texte .= '<dt> Quotité travaillée </dt>';
        $texte .= '<dd> <span class="TODO"> Information manquante </span> </dd>';
        $texte .= '<dt> Correspondance </dt>';
        $texte .= '<dd> <span class="TODO"> Information manquante </span> </dd>';
        $texte .= '<dt> Corps </dt>';
        $texte .= '<dd> <span class="TODO"> Information manquante </span> </dd>';
        $texte .= '<dt> Grade </dt>';
        $texte .= '<dd> <span class="TODO"> Information manquante </span> </dd>';
        $texte .= '</dl>';

        $texte .= '<h3>Statuts</h3>';
        foreach ($agent->getStatuts() as $statut) {
            $texte .= $this->getView()->agentStatut()->render($statut, ['show_agent' => false]);
        }

        $texte .= '<h3>Missions spécifiques</h3>';

        $texte .= '<ul>';
        foreach ($agent->getMissions() as $mission) {
            $texte .= '<li>'. $mission->getLibelle().'</li>';
        }
        $texte .= '</ul>';

        return $texte;
    }
}