<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Poste;
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
        $texte  = '<dl class="dl-horizontal">';
        $texte .= '<dt> Prénom </dt>';
        $texte .= '<dd> '. $agent->getPrenom() . '</dd>';
        $texte .= '<dt> Nom </dt>';
        $texte .= '<dd> '. $agent->getNom() .'</dd>';
        $texte .= '<dt> Numéro poste </dt>';
        $texte .= '<dd> '.$agent->getNumeroPoste() .'</dd>';
        $texte .= '<dt> Date de début </dt>';
        $texte .= '<dd>'. (($agent->getDateDebut())?$agent->getDateDebut()->format('d/m/Y'):'---').'</dd>';
        $texte .= '<dt> Date de fin </dt>';
        $texte .= '<dd>'. (($agent->getDateFin())?$agent->getDateFin()->format('d/m/Y'):'---').'</dd>';
        $texte .= '<dt> Quotité travaillée </dt>';
        $texte .= '<dd>'. $agent->getQuotite().'% </dd>';
        $texte .= '<dt> Status </dt>';
        $texte .= '<dd> '. $agent->getStatus() .'</dd>';
        $texte .= '<dt> Correspondance </dt>';
        $texte .= '<dd> '. $agent->getCorrespondance() .'</dd>';
        $texte .= '<dt> Corps </dt>';
        $texte .= '<dd> '. $agent->getCorps() .'</dd>';
        $texte .= '<dt> Grade </dt>';
        $texte .= '<dd> '. $agent->getGrade()->getLibelle() .'</dd>';
        $texte .= '</dl>';
        $texte .= '<br/>';

         if ($agent->getMissionsComplementaires() !== null) {
             $texte .= '<h3>Missions complémentaires</h3>';
             $texte .= $agent->getMissionsComplementaires();
         }

        return $texte;
    }
}