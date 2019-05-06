<?php

namespace Application\Controller\Fonction;

use Application\Entity\Db\Fonction;
use Application\Entity\Db\FonctionLibelle;
use Application\Entity\Db\Source;
use Application\Form\Fonction\FonctionFormAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FonctionController extends AbstractActionController {
    use FonctionServiceAwareTrait;

    use FonctionFormAwareTrait;

    public function indexAction()
    {
        $fonctions = $this->getFonctionService()->getFonctions();

        return new ViewModel([
            'fonctions' => $fonctions,
        ]);
    }

    public function creerAction()
    {
        $fonction = new Fonction();
        $form = $this->getFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/creer', [], [], true));
        $form->bind($fonction);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            $fonction->setSource(Source::Preecog);
            $this->getFonctionService()->create($fonction);
            {
                $libelle = new FonctionLibelle();
                $libelle->setFonction($fonction);
                $libelle->setLibelle(trim($data['libelle_masculin']));
                $libelle->setDefault('O');
                $libelle->setGenre('M');
                $this->getFonctionService()->createLibelle($libelle);
                $fonction->addLibelle($libelle);
            }
            {
                $libelle = new FonctionLibelle();
                $libelle->setFonction($fonction);
                $libelle->setLibelle(trim($data['libelle_feminin']));
                $libelle->setDefault('O');
                $libelle->setGenre('F');
                $this->getFonctionService()->createLibelle($libelle);
                $fonction->addLibelle($libelle);
            }

            $this->getFonctionService()->update($fonction);
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'une fonction',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFontion($this, 'fonction');
        $form = $this->getFonctionForm();
        $form->setAttribute('action', $this->url()->fromRoute('fonction/modifier', ['fonction' => $fonction->getId()], [], true));
        $form->bind($fonction);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            {
                $libelle = $fonction->getDefault('M');
                $libelle->setLibelle(trim($data['libelle_masculin']));
                $this->getFonctionService()->updateLibelle($libelle);
            }
            {
                $libelle = $fonction->getDefault('F');
                $libelle->setLibelle(trim($data['libelle_feminin']));
                $this->getFonctionService()->updateLibelle($libelle);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'une fonction',
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFontion($this, 'fonction');
        return new ViewModel([
            'title' => 'Visualisation d\'une fonction',
            'fonction' => $fonction,
        ]);
    }

    public function historiserAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFontion($this, 'fonction');
        $this->getFonctionService()->historise($fonction);
        return $this->redirect()->toRoute('fonction',[], [], true);
    }

    public function restaurerAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFontion($this, 'fonction');
        $this->getFonctionService()->restore($fonction);
        return $this->redirect()->toRoute('fonction',[], [], true);
    }

    public function detruireAction()
    {
        $fonction = $this->getFonctionService()->getRequestedFontion($this, 'fonction');
        $this->getFonctionService()->delete($fonction);
        return $this->redirect()->toRoute('fonction',[], [], true);
    }

    public function synchroniserAction()
    {
        $result = $this->getFonctionService()->synchroniseFromOctopus();
        return $this->redirect()->toRoute('fonction',[], [], true);
    }
}