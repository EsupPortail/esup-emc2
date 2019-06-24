<?php

namespace Application\View\Helper;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FicheMetierTypeActivite;
use Zend\View\Helper\AbstractHelper;

class FicheTypeViewHelper extends AbstractHelper
{

    /**
     * @param FicheMetier $ficheMetier
     * @return string
     */
    public function render($ficheMetier)
    {
        $activites = $ficheMetier->getActivites();
        usort($activites, function (FicheMetierTypeActivite $a, FicheMetierTypeActivite $b) { return $a->getPosition() < $b->getPosition();});

        $texte  = '';

        $texte .= '<h2>'.$ficheMetier->getMetier()->getLibelle().'</h2>';

        $texte .= '    <div class="panel panel-info">';
        $texte .= '         <div class="panel-heading">';
        $texte .= '              <h2>Missions principales</h2>';
        $texte .= '         </div>';
        $texte .= '         <div class="panel-body">';
        $texte .= '              ' . $ficheMetier->getMissionsPrincipales();
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
        $texte .= '                  ' . $ficheMetier->getConnaissances();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetier->getConnaissancesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Compétences opérationnelles </h4>';
        $texte .= '                  ' . $ficheMetier->getCompetencesOperationnelles();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetier->getCompetencesOperationnellesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Compétences comportementales </h4>';
        $texte .= '                  ' . $ficheMetier->getCompetencesComportementales();
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetier->getCompetencesComportementalesFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';

        $applications = [];
        foreach ($ficheMetier->getActivites() as $activite) {
            foreach ($activite->getActivite()->getApplications() as $application) {
                $applications[] = $application->getLibelle();
            }
        }
        foreach ($ficheMetier->getApplications() as $application) {
            $applications[] = $application->getLibelle();
        }
        sort($applications);
        $applications = array_unique($applications);

        $texte .= '         <div class="row">';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Applications </h4>';
        $texte .= '                  <ul>';

                                    foreach ($applications as $application) {
                                        $texte .= '<li>'.$application.'</li>';
                                    }

        $texte .= '                  </ul>';
        $texte .= '             </div>';
        $texte .= '             <div class="col-md-6">';
        $texte .= '                  <h4> Plan de formation </h4>';
        $texte .= '                  ' . $ficheMetier->getApplicationsFormation();
        $texte .= '             </div>';
        $texte .= '         </div>';
        $texte .= '         </div>';
        $texte .= '    </div>';


        return $texte;

    }
}