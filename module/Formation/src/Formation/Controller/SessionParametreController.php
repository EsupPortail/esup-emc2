<?php

namespace Formation\Controller;

use Formation\Entity\Db\SessionParametre;
use Formation\Form\SessionParametre\SessionParametreFormAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Formation\Service\SessionParametre\SessionParametreServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SessionParametreController extends AbstractActionController
{
    use SessionServiceAwareTrait;
    use SessionParametreServiceAwareTrait;
    use SessionParametreFormAwareTrait;

    public function modifierAction(): ViewModel
    {
        $session = $this->getSessionService()->getRequestedSession($this, 'session');

        $parametre = $session->getParametre();
        if ($parametre === null) {
            $parametre = new SessionParametre();
            $parametre = $this->getSessionParametreService()->create($parametre);
            $session->setParametre($parametre);
            $this->getSessionService()->update($session);
        }

        $form = $this->getSessionParametreForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/session-parametre', ['session' => $session->getId()], [], true));
        $form->bind($parametre);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getSessionParametreService()->update($parametre);
            }
        }

        $vm = new ViewModel([
            'title' => "Paramètre de la session de formation",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/default/default-form');
        return $vm;
    }
}