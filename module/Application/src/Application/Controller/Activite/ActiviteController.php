<?php

namespace Application\Controller\Activite;

use Application\Entity\Db\Activite;
use Application\Form\Activite\ActiviteForm;
use Application\Form\Activite\ActiviteFormAwareTrait;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActiviteController  extends AbstractActionController {
    /** Traits associé aux services */
    use ActiviteServiceAwareTrait;
    /** Traits associé aux formulaires */
    use ActiviteFormAwareTrait;

    public function indexAction()
    {
        /** @var Activite[] $activites */
        $activites = $this->getActiviteService()->getActivites('id');

        return new ViewModel([
            'activites' => $activites,
        ]);
    }

    public function creerAction()
    {
        /** @var Activite $activite */
        $activite = new Activite();

        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/creer',[],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);
//                $this->redirect()->toRoute('activite');
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une activité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        /** @var Activite $activite */
        $activiteId = $this->params()->fromRoute('id');
        $activite = $this->getActiviteService()->getActivite($activiteId);

        /** @var ActiviteForm $form */
        $form = $this->getActiviteForm();
        $form->setAttribute('action', $this->url()->fromRoute('activite/editer',['id' => $activite->getId()],[], true));
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->update($activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une activité',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerAction()
    {
        /** @var Activite $activite */
        $activiteId = $this->params()->fromRoute('id');
        $activite = $this->getActiviteService()->getActivite($activiteId);

        $this->getActiviteService()->delete($activite);
        $this->redirect()->toRoute('activite');
    }
}