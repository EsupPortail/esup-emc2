<?php

namespace Indicateur\Controller;

use Indicateur\Entity\Db\Abonnement;
use Indicateur\Service\Abonnement\AbonnementServiceAwareTrait;
use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class IndexController extends AbstractActionController {
    use AbonnementServiceAwareTrait;
    use IndicateurServiceAwareTrait;
    use UserServiceAwareTrait;

    public function indexAction() : ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $abonnements = $this->getAbonnementService()->getAbonnementsByUser($user);

        $result = [];
        foreach ($abonnements as $abonnement) {
            $indicateur = $abonnement->getIndicateur();
            $data = $this->getIndicateurService()->getIndicateurData($indicateur);
            $result[$indicateur->getId()] = count($data[1]);
        }

        return new ViewModel([
            'abonnements' => $abonnements,
            'result' => $result,
        ]);
    }

    public function abonnementAction()
    {
        $user = $this->getUserService()->getConnectedUser();
        $indicateurs = $this->getIndicateurService()->getIndicateurs();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();

            $id = $data['indicateur'];
            $indicateur = $this->getIndicateurService()->getIndicateur($id);

            if (!$this->getAbonnementService()->isAbonner($user, $indicateur)) {
                $abonnement = new Abonnement();
                $abonnement->setUser($user);
                $abonnement->setIndicateur($indicateur);
//                $abonnement->setFrequence('P1W');
                $this->getAbonnementService()->create( $abonnement );
            }

            exit();
        }

        return new ViewModel([
            'title' => "S'abonner à un indicateur",
            'indicateurs' => $indicateurs,
        ]);
    }

}