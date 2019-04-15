<?php

namespace Application\View\Helper;


use Application\Entity\Db\Application;use Application\Entity\Db\FicheTypeExterne;
use Zend\View\Helper\AbstractHelper;

class FicheTypeExterneViewHelper extends AbstractHelper
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
     * @param FicheTypeExterne $ficheTypeExterne
     * @return string
     */
    public function render($ficheTypeExterne, $actionnable = true)
    {
        $texte  = '';
        $texte .= '<div class="panel panel-info">';
        $texte .= '    <div class="panel-heading">';
        $texte .= '         <div class="row">';
        $texte .= '              <div class="col-md-6">';
        $texte .= '                   <h2>'.$ficheTypeExterne->getFicheType()->getMetier()->getLibelle().'</h2>';
        $texte .= '              </div>';
        $texte .= '              <div class="col-md-1 pull-right">';
        if ($actionnable === true) {
            $texte .= '                   <a href="' . $this->getView()->url('fiche-poste/modifier-fiche-metier', ['fiche-poste' => $ficheTypeExterne->getFichePoste()->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true) . '" class="ajax-modal" data-event="modification">';
            $texte .= '                   <span class="icon editer" title="Éditer la fiche type externe"></span></a>';
            $texte .= '                   <a href="' . $this->getView()->url('fiche-poste/selectionner-activite', ['fiche-poste' => $ficheTypeExterne->getFichePoste()->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true) . '" class="ajax-modal" data-event="modification">';
            $texte .= '                   <span class="icon listing" title="Sélectionner les activités associées"></span></a>';
            $texte .= '                   <a href="' . $this->getView()->url('fiche-poste/retirer-fiche-metier', ['fiche-poste' => $ficheTypeExterne->getFichePoste()->getId(), 'fiche-type-externe' => $ficheTypeExterne->getId()], [], true) . '">';
            $texte .= '                   <span class="icon detruire" title="Retirer la fiche type externe"></span></a>';
        }
        $texte .= '              </div>';
        $texte .= '              <div class="col-md-3 pull-right">';
        if ($ficheTypeExterne->getPrincipale()) {
            $texte .='Principale';
        } else {
            $texte .=$ficheTypeExterne->getQuotite().'%';
        }
        $texte .= '              </div>';
        $texte .= '         </div>';
        $texte .= '    </div>';
        $texte .= '    <div class="panel-body">';
        if ($ficheTypeExterne->getPrincipale()) {
            $texte .= '         <h3>Missions principales</h3>';
            $texte .= '         ' . $ficheTypeExterne->getFicheType()->getMissionsPrincipales();

            $texte .= '         <h3>Parcours de formation de base pour la prise de poste </h3>';

            $texte .= '         <div class="row">';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Connaissances </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getConnaissances();
            $texte .= '             </div>';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Plan de formation </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getConnaissancesFormation();
            $texte .= '             </div>';
            $texte .= '         </div>';

            $texte .= '         <div class="row">';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Compétences opérationnelles </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getCompetencesOperationnelles();
            $texte .= '             </div>';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Plan de formation </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getCompetencesOperationnellesFormation();
            $texte .= '             </div>';
            $texte .= '         </div>';

            $texte .= '         <div class="row">';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Compétences comportementales </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getCompetencesComportementales();
            $texte .= '             </div>';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Plan de formation </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getCompetencesComportementalesFormation();
            $texte .= '             </div>';
            $texte .= '         </div>';

            $texte .= '         <div class="row">';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Applications </h4>';
            $texte .= '                  <ul>';
            foreach ($ficheTypeExterne->getFicheType()->getApplications() as $application) {
                $texte .= '<li>'.$application->getLibelle().'</li>';
            }
            $texte .= '                  </ul>';
            $texte .= '             </div>';
            $texte .= '             <div class="col-md-6">';
            $texte .= '                  <h4> Plan de formation </h4>';
            $texte .= '                  ' . $ficheTypeExterne->getFicheType()->getApplicationsFormation();
            $texte .= '             </div>';
            $texte .= '         </div>';
        }

        $texte .= '         <h3> Activités </h3>';
        $activites = explode(";", $ficheTypeExterne->getActivites());
        foreach($ficheTypeExterne->getFicheType()->getActivites() as $activite) {
            $idActivite = $activite->getActivite()->getId();
            if (array_search($idActivite, $activites) !== false) {
                $texte .= '<h4>' . $activite->getActivite()->getLibelle() . '</h4>';
                $texte .= $activite->getActivite()->getDescription();
            }
        }

        $texte .= '    </div>';
        $texte .= '</div>';

        return $texte;
    }
}