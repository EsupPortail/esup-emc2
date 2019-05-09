<?php

namespace Application\View\Helper;

use Application\Entity\Db\AgentStatut;
use Zend\View\Helper\AbstractHelper;

class AgentStatutViewHelper extends AbstractHelper
{

    /**
     * @param AgentStatut $statut
     * @return string
     */
    public function render($statut, $args = [])
    {
        $texte  = '';

        $texte .= '<div style="border: solid grey 1px; margin: 2px; padding: 2px; border-radius: 5px;">';

        if (!isset($args['show_id']) || $args['show_id'] !== false) $texte .= '<span class="badge">'. $statut->getIdOrigine(). '</span> ';
        if (!isset($args['show_agent']) || $args['show_agent'] !== false) $texte .= '<span class="badge">'. $statut->getAgent()->getDenomination(). '</span> ';
        if (!isset($args['show_structure']) || $args['show_structure'] == true) $texte .= '<span class="badge">'. $statut->getStructure()->getLibelleLong(). '</span> ';
        $texte .= ($statut->getDebut())?$statut->getDebut()->format('d/m/Y'):'---';
        $texte .= '&rarr;';
        $texte .= ($statut->getFin())?$statut->getFin()->format('d/m/Y'):'---';

        $texte .= '<ul>';
        if ($statut->isTitulaire())         $texte .='<li>Titulaire</li>';
        if ($statut->isCdi())               $texte .='<li>C.D.I.</li>';
        if ($statut->isCdd())               $texte .='<li>C.D.D.</li>';
        if ($statut->isVacataire())         $texte .='<li>Vacataire</li>';
        if ($statut->isEnseignant())        $texte .='<li>Enseignant</li>';
        if ($statut->isAdministratif())     $texte .='<li>Administratif</li>';
        if ($statut->isChercheur())         $texte .='<li>Chercheur</li>';
        if ($statut->isEtudiant())          $texte .='<li>Etudiant</li>';
        if ($statut->isAuditeurLibre())     $texte .='<li>Auditeur libre</li>';
        if ($statut->isDoctorant())         $texte .='<li>Doctorant</li>';
        if ($statut->isDetacheIn())         $texte .='<li>Détaché (in)</li>';
        if ($statut->isDetacheOut())        $texte .='<li>Détaché (out)</li>';
        if ($statut->isDispo())             $texte .='<li>Disponibilité</li>';
        if ($statut->isHeberge())           $texte .='<li>Hébergé</li>';
        if ($statut->isEmerite())           $texte .='<li>Émerite</li>';
        if ($statut->isRetraite())          $texte .='<li>Retraité</li>';
        $texte .= '</ul>';

        $texte .= '</div>';

        return $texte;

    }
}