<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentStatut;
use Application\View\Helper\AgentStatus\AgentStatutViewHelper;
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

        $correspondance = ($agent->getCorrespondance()) ? ($agent->getCorrespondance()->getLibelleCourt())  . " - "     . $agent->getCorrespondance()->getLibelleLong()     : "---";
        $corps          = ($agent->getCorps())          ? ($agent->getCorps()->getLibelleCourt())           . " - "     . $agent->getCorps()->getLibelleLong()              : "---";
        $grade          = ($agent->getGrade())          ? ($agent->getGrade()->getLibelleCourt())           . " - "     . $agent->getGrade()->getLibelleLong()              : "---";

        $texte  = '';
        $texte .= '<h3>Présentation de l\'agent </h3>';

        $texte .= '<dl class="dl-horizontal">';
        $texte .= '<dt> Prénom </dt>';
        $texte .= '<dd> '. $agent->getPrenom() . '</dd>';
        $texte .= '<dt> Nom </dt>';
        $texte .= '<dd> '. $agent->getNomUsuel() .'</dd>';
        $texte .= '<dt> Quotité travaillée </dt>';
        $texte .= '<dd>'. (($agent->getQuotite() === null)?"Non renseignée":$agent->getQuotite() . " %").'</dd>';
        $texte .= '<dt> Correspondance </dt>';
        $texte .= '<dd>' . $correspondance.'</dd>';
        $texte .= '<dt> Corps </dt>';
        $texte .= '<dd>' . $corps . '</dd>';
        $texte .= '<dt> Grade </dt>';
        $texte .= '<dd>' . $grade . '</dd>';
        $texte .= '</dl>';

        $texte .= '<h3>Statuts</h3>';
        $statuts = $agent->getStatuts();
        usort($statuts, function (AgentStatut $a, AgentStatut $b) { return $a->getDebut() < $b->getDebut();});
        foreach ($statuts as $statut) {
            $texte .= $this->getView()->agentStatut($statut, ['denomination' => false]);
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