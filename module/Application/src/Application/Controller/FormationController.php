<?php

namespace Application\Controller;

use Application\Entity\Db\Formation;
use Application\Form\Formation\FormationFormAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationController extends AbstractActionController
{
    use FormationServiceAwareTrait;
    use FormationFormAwareTrait;

    public function indexAction()
    {
        $formations = $this->getFormationService()->getFormations('libelle');
        return new ViewModel([
            'formations' => $formations,
        ]);
    }

    public function afficherAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        return new ViewModel([
            'title' => 'Affichage de la formation ['.$formation->getLibelle().']',
            'formation' => $formation,
        ]);
    }

    public function ajouterAction()
    {
        $formation = new Formation();
        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/ajouter', [], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->create($formation);
                exit;
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function editerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
//        if ($formation === null) $formation = $this->getFormationService()->getLastFormation();

        $form = $this->getFormationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/editer', ['formation' => $formation->getId()], [], true));
        $form->bind($formation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationService()->update($formation);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Edition d\'une formation',
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->historise($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

    public function restaurerAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->restore($formation);
        return $this->redirect()->toRoute('formation', [], [], true);

    }

    public function detruireAction()
    {
        $formation = $this->getFormationService()->getRequestedFormation($this);
        $this->getFormationService()->delete($formation);
        return $this->redirect()->toRoute('formation', [], [], true);
    }

}