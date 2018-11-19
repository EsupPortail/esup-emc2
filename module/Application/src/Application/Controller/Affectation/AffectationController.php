<?php

namespace Application\Controller\Affectation;

use Application\Entity\Db\Affectation;
use Application\Form\Affectation\AffectationForm;
use Application\Service\Affectation\AffectationAwareServiceTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AffectationController extends AbstractActionController {
    use AffectationAwareServiceTrait;

    public function indexAction()
    {
        $affectations = $this->getAffectationService()->getAffections();
        return new ViewModel([
            'affectations' => $affectations,
        ]);
    }

    public function creerAction()
    {
        /** @var Affectation $affectation */
        $affectation = new Affectation();

        /** @var AffectationForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AffectationForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('affectation/creer',[],[], true));
        $form->bind($affectation);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getAffectationService()->create($affectation);
//                $this->redirect()->toRoute('affectation');
            }
        }

        return new ViewModel([
            'title' => 'Ajouter une affectation',
            'form' => $form,
        ]);
    }

    public function editerAction()
    {
        /** @var Affectation $affectation */
        $affectationId = $this->params()->fromRoute('id');
        $affectation = $this->getAffectationService()->getAffectation($affectationId);

        /** @var AffectationForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AffectationForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('affectation/editer',['id' => $affectation->getId()],[], true));
        $form->bind($affectation);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getAffectationService()->update($affectation);
//                $this->redirect()->toRoute('affectation');
            }
        }

        return new ViewModel([
            'title' => 'Éditer une affectation',
            'form' => $form,
        ]);
    }

    public function effacerAction()
    {
        /** @var Affectation $affectation */
        $affectationId = $this->params()->fromRoute('id');
        $affectation = $this->getAffectationService()->getAffectation($affectationId);

        $this->getAffectationService()->delete($affectation);
        $this->redirect()->toRoute('affectation');
    }
}