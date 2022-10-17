<?php

namespace Indicateur\Controller;

use DateInterval;
use DateTime;
use Indicateur\Entity\Db\Abonnement;
use Indicateur\Service\Abonnement\AbonnementServiceAwareTrait;
use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

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
        $retour = $this->params()->fromQuery('retour');

        $abonnements = $this->getAbonnementService()->getAbonnementsByUserAndIndicateur($user, $indicateur);
        foreach ($abonnements as $abonnement) $this->getAbonnementService()->delete($abonnement);

        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function notifierAction()
    {
        $this->getAbonnementService()->notifyAbonnements();
        return $this->redirect()->toRoute('indicateurs', [], [], true);
    }

    public function notifierConsoleAction()
    {
        $this->getAbonnementService()->notifyAbonnements();
        exit();
    }
}