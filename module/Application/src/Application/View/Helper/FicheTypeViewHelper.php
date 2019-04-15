<?php

namespace Application\View\Helper;

use Application\Entity\Db\Application;
use Application\Entity\Db\FicheMetierType;
use Application\Entity\Db\FicheMetierTypeActivite;
use Zend\View\Helper\AbstractHelper;

class FicheTypeViewHelper extends AbstractHelper
{

    /**
     * @param FicheMetierType $ficheMetierType
     * @return string
     */
    public function render($ficheMetierType)
    {
        $activites = $ficheMetierType->getActivites();
        usort($activites, function (FicheMetierTypeActivite $a, FicheMetierTypeActivite $b) { return $a->getPosition() < $b->getPosition();});

        $texte  = '';

        $texte .= '<h2>'.$ficheMetierType->getMetier()->getLibelle().'</h2>';

        $texte .= '    <div class="panel panel-info">';
        $texte .= '         <div class="panel-heading">';
        $texte .= '              <h2>Missions principales</h2>';
        $texte .= '         </div>';
        $texte .= '         <div class="panel-body">';
        $texte .= '              ' . $ficheMetierType->getMissionsPrincipales();
        $texte .= '         </div>';
        $texte .= '    </div>';



        $texte .= '    <div class="panel panel-info">';
        $texte .= '         <div class="panel-heading">';
        $texte .= '              <h2>Activités</h2>';
        $texte .= '         </div>';
        $texte .= '         <div class="panel-body">';
        foreach($activites as $activite) {
            if ($activite->getActivite()->estNonHistorise()) {
                $texte .= '<div class="activite">';
                $texte .= '<h3>' . $activite->getActivite()->getLibelle() . '</h3>';
                $texte .= $activite->getActivite()->getDescription();
                $texte .= '</div>';
            }
        }
        $texte .= '         </div>';
        $texte .= '    </div>';

        $texte .= '    <div class="panel panel-info">';
        $texte .= '         <div class="panel-heading">';
        $texte .= '              <h2>Parcours de formation de base pour la prise de poste</h2>';
        $texte .= '         </div>';
        $texte .= '         <div class="panel-body">';
        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Connaissances </h4>';
        $texte .= '                  ' . $ficheMetierType->getConnaissances();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetierType->getConnaissancesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Compétences opérationnelles </h4>';
        $texte .= '                  ' . $ficheMetierType->getCompetencesOperationnelles();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetierType->getCompetencesOperationnellesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Compétences comportementales </h4>';
        $texte .= '                  ' . $ficheMetierType->getCompetencesComportementales();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetierType->getCompetencesComportementalesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Applications </h4>';
        $texte .= '                  <ul>';

                                    foreach ($ficheMetierType->getApplications() as $application) {
                                        $texte .= '<li>'.$application->getLibelle().'</li>';
                                    }

        $texte .= '                  </ul>';
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetierType->getApplicationsFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';
        $texte .= '         </div>';
        $texte .= '    </div>';


        return $texte;

    }
}