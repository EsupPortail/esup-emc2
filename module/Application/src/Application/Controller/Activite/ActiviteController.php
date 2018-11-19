<?php

namespace Application\Controller\Activite;

use Application\Entity\Db\Activite;
use Application\Form\Activite\ActiviteForm;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ActiviteController  extends AbstractActionController {
    use ActiviteServiceAwareTrait;

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
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);
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

        return new ViewModel([
            'title' => 'Ajouter une activité',
            'form' => $form,
        ]);
    }

    public function editerAction()
    {
        /** @var Activite $activite */
        $activiteId = $this->params()->fromRoute('id');
        $activite = $this->getActiviteService()->getActivite($activiteId);

        /** @var ActiviteForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('activite/editer',['id' => $activite->getId()],[], true));
        $form->bind($activite);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->update($activite);
//                $this->redirect()->toRoute('activite');
            }
        }

        return new ViewModel([
            'title' => 'Éditer une activité',
            'form' => $form,
        ]);
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