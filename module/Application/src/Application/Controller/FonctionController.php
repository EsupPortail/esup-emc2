<?php

namespace Application\Controller;

use Application\Entity\Db\FonctionActivite;
use Application\Entity\Db\FonctionDestination;
use Application\Form\FonctionActivite\FonctionActiviteFormAwareTrait;
use Application\Form\FonctionDestination\FonctionDestinationFormAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class FonctionController extends AbstractActionController {
    use FonctionServiceAwareTrait;
    use FonctionActiviteFormAwareTrait;
    use FonctionDestinationFormAwareTrait;

    public function indexAction()
    {
        $destinations = $this->getFonctionService()->getDestinations();
        $activites = $this->getFonctionService()->getActivites();

        return new ViewModel([
            'destinations' => $destinations,
            'activites' => $activites,
        ]);
    }

    public function ajouterDestinationAction()
    {
        $destination = new FonctionDestination();

        $form = $this->getFonctionDestinationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/destination/ajouter', [], [], true));
        $form->bind($destination);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionService()->createDestination($destination);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une nouvelle destination",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierDestinationAction()
    {
        $destination = $this->getFonctionService()->getResquestedDestrination($this);

        $form = $this->getFonctionDestinationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/destination/modifier', ['destination' => $destination->getId()], [], true));
        $form->bind($destination);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionService()->updateDestination($destination);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une destination",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerDestinationAction()
    {
        $destination = $this->getFonctionService()->getResquestedDestrination($this);
        $this->getFonctionService()->deleteDestination($destination);

        return $this->redirect()->toRoute('fonction');
    }

    public function ajouterActiviteAction()
    {
        $activite = new FonctionActivite();

        $form = $this->getFonctionActivitenForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/activite/ajouter', [], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionService()->createActivite($activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une nouvelle activite",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierActiviteAction()
    {
        $activite = $this->getFonctionService()->getResquestedActivite($this);

        $form = $this->getFonctionActivitenForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/activite/modifier', ['activite' => $activite->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFonctionService()->updateActivite($activite);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une activite",
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerActiviteAction()
    {
        $activite = $this->getFonctionService()->getResquestedActivite($this);
        $this->getFonctionService()->deleteActivite($activite);

        return $this->redirect()->toRoute('fonction');
    }
}