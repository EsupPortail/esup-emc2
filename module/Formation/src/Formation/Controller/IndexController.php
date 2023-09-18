<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Provider\Template\TextTemplates;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IndexController extends AbstractActionController
{

    use AgentServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UserServiceAwareTrait;


    public function indexAction(): ViewModel
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($connectedUser);
        if ($agent !== null && $agent->getUtilisateur() === null) {
            $previous = $agent->getUtilisateur();
            $agent->setUtilisateur($connectedUser);
            $this->getAgentService()->update($agent);

            if ($connectedUser !== $previous) {
                $this->redirect()->toRoute('home');
            }
        }

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::MES_FORMATIONS_ACCUEIL, [], false);

        return new ViewModel([
            'agent' => $agent,
            'user' => $connectedUser,
            'role' => $this->getUserService()->getConnectedRole(),
            'rendu' => $rendu,
        ]);
    }

    public function aproposAction(): ViewModel
    {
        return new ViewModel([]);
    }

    public function contactAction(): ViewModel
    {
        return new ViewModel([]);
    }
}