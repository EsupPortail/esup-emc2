<?php

namespace Application\Controller;

use Application\Entity\Db\Poste;
use Application\Form\Poste\PosteForm;
use Application\Form\Poste\PosteFormAwareTrait;
use Application\Service\Poste\PosteServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class PosteController extends AbstractActionController {
    /** Trait utilisés pour les services */
    use PosteServiceAwareTrait;
    /** Trait utilisés pour les formulaires */
    use PosteFormAwareTrait;

    public function indexAction()
    {
        $postes = $this->getPosteService()->getPostes();
        
        return new ViewModel([
            'postes' => $postes,
        ]);
    }

    public function afficherAction()
    {
        /** @var Poste $poste */
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        return new ViewModel([
            'title' => 'Affichage du poste',
            'poste' => $poste,
        ]);
    }

    public function ajouterAction()
    {
        /** @var Poste $poste */
        $poste = new Poste();

        /** @var PosteForm $form */
        $form = $this->getPosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('poste/ajouter', [], [], true));
        $form->bind($poste);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPosteService()->create($poste);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajouter un poste",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        /** @var Poste $poste */
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        /** @var PosteForm $form */
        $form = $this->getPosteForm();
        $form->setAttribute('action', $this->url()->fromRoute('poste/modifier', ['poste' => $poste->getId()], [], true));
        $form->bind($poste);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getPosteService()->update($poste);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modifier un poste",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerAction()
    {
        $posteId = $this->params()->fromRoute('poste');
        $poste = $this->getPosteService()->getPoste($posteId);

        if ($poste) {
            $this->getPosteService()->delete($poste);
        }

        return $this->redirect()->toRoute('poste', [], [], true);
    }
}