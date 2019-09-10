<?php

namespace Indicateur\Controller;

use DateInterval;
use DateTime;
use Indicateur\Entity\Db\Abonnement;
use Indicateur\Service\Abonnement\AbonnementServiceAwareTrait;
use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AbonnementController extends AbstractActionController {
    use AbonnementServiceAwareTrait;
    use IndicateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction()
    {
        $abonnements = [];

        return new ViewModel([
            'abonnements' => $abonnements,
        ]);
    }

    public function souscrireAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $user = $this->getUserService()->getConnectedUser();

        $abonnement = new Abonnement();
        $abonnement->setIndicateur($indicateur);
        $abonnement->setUser($user);
        $abonnement->setFrequence('P1D');
        $this->getAbonnementService()->create($abonnement);

        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function resilierAction()
    {
        $indicateur = $this->getIndicateurService()->getRequestedIndicateur($this);
        $user = $this->getUserService()->getConnectedUser();

        $abonnements = $this->getAbonnementService()->getAbonnementsByUserAndIndicateur($user, $indicateur);
        foreach ($abonnements as $abonnement) $this->getAbonnementService()->delete($abonnement);

        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function notifierAction()
    {
        $abonnements = $this->getAbonnementService()->getAbonnements();
        $now = new DateTime();
        $listing = [];
        foreach ($abonnements as $abonnement) {
            $deadline = null;
            if ($abonnement->getDernierEnvoi() !== null) {
                $deadline = ($abonnement->getDernierEnvoi())->add(DateInterval::createFromDateString("".$abonnement->getFrequence()));
            }
            if ($abonnement->getDernierEnvoi() === null || $deadline < $now) {
                $listing[$abonnement->getIndicateur()->getId()][] = $abonnement;
            }
        }
        foreach ($listing as $indicateurId => $list) {
            $indicateur = $this->getIndicateurService()->getIndicateur($indicateurId);
            $titre = "Publication de l'indicateur [".$indicateur->getTitre()."] (". $now->format("d/m/Y à H:i:s").")";
            $result = $this->getIndicateurService()->getIndicateurData($indicateur);
            $texte  = "<table>";
            $texte .= "<thead>";
            $texte .= "<tr>";
            foreach ($result[0] as $rubrique) $texte .= "<th>" . $rubrique . "</th>";
            $texte .= "</tr>";
            $texte .= "</thead>";
            $texte .= "<tbody>";
            foreach ($result[1] as $item) {
                $texte .="<tr>";
                foreach ($item as $value) $texte .="<td>". $value ."</td>";
                $texte .="</tr>";
            }
            $texte .= "</tbody>";
            $texte .= "</table>";

            foreach ($list as $abonnement) {
                $this->getAbonnementService()->notify($abonnement, $titre, $texte, $now);
                $abonnement->setDernierEnvoi($now);
                $this->getAbonnementService()->update($abonnement);
            }
        }
        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function notifierConsoleAction()
    {
        $abonnements = $this->getAbonnementService()->getAbonnements();
        $now = new DateTime();
        $listing = [];
        foreach ($abonnements as $abonnement) {
            $deadline = null;
            if ($abonnement->getDernierEnvoi() !== null) {
                $deadline = ($abonnement->getDernierEnvoi())->add(DateInterval::createFromDateString($abonnement->getFrequence()));
            }
            if ($abonnement->getDernierEnvoi() === null || $deadline < $now) {
                $listing[$abonnement->getIndicateur()->getId()][] = $abonnement;
            }
        }
        foreach ($listing as $indicateurId => $list) {
            $indicateur = $this->getIndicateurService()->getIndicateur($indicateurId);
            $titre = "Publication de l'indicateur [".$indicateur->getTitre()."] (". $now->format("d/m/Y à H:i:s").")";
            $result = $this->getIndicateurService()->getIndicateurData($indicateur);
            $texte  = "<table>";
            $texte .= "<thead>";
            $texte .= "<tr>";
            foreach ($result[0] as $rubrique) $texte .= "<th>" . $rubrique . "</th>";
            $texte .= "</tr>";
            $texte .= "</thead>";
            $texte .= "<tbody>";
            foreach ($result[1] as $item) {
                $texte .="<tr>";
                foreach ($item as $value) $texte .="<td>". $value ."</td>";
                $texte .="</tr>";
            }
            $texte .= "</tbody>";
            $texte .= "</table>";

            foreach ($list as $abonnement) {
                $this->getAbonnementService()->notify($abonnement, $titre, $texte, $now);
                $abonnement->setDernierEnvoi($now);
                $this->getAbonnementService()->update($abonnement);
            }
        }
        exit();
    }
}