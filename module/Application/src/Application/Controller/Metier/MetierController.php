<?php

namespace Application\Controller\Metier;

use Application\Entity\Db\Metier;
use Application\Form\Metier\MetierForm;
use Application\Service\Metier\MetierServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class MetierController extends AbstractActionController {
    use MetierServiceAwareTrait;

    public function indexAction()
    {
        /** @var Metier[] $metiers */
        $metiers = $this->getMetierService()->getMetiers('libelle');

        return new ViewModel([
            'metiers' => $metiers,
        ]);
    }

    public function creerAction()
    {
        /** @var Metier $metier */
        $metier = new Metier();

        /** @var MetierForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('metier/creer', [], [], true));
        $form->bind($metier);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getMetierService()->create($metier);
                //$this->redirect()->toRoute('application');
            }
        }

        return new ViewModel([
            'title' => 'Ajouter un métier',
            'form' => $form,
        ]);
    }
    public function editerAction()
    {
        /** @var Metier $metier */
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getMetierService()->getMetier($metierId);

        /** @var MetierForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('metier/editer', ['id' => $metier->getId()], [], true));
        $form->bind($metier);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getMetierService()->update($metier);
                //$this->redirect()->toRoute('application');
            }
        }

        return new ViewModel([
            'title' => 'Éditer un métier',
            'form' => $form,
        ]);
    }
    public function effacerAction()
    {
        /** @var Metier $metier */
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getMetierService()->getMetier($metierId);

        $this->getMetierService()->delete($metier);
        $this->redirect()->toRoute('metier');
    }
}