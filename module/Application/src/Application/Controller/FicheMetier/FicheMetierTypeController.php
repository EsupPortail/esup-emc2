<?php

namespace Application\Controller\FicheMetier;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetierTypeActivite;
use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetierType\ActiviteExistanteForm;
use Application\Form\FicheMetierType\LibelleForm;
use Application\Form\FicheMetierType\MissionsPrincipalesForm;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheMetierTypeController extends  AbstractActionController{
    use ActiviteServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    public function indexAction()
    {
        $fichesMetiersTypes = $this->getFicheMetierService()->getFichesMetiersTypes();

        return new ViewModel([
            'fiches' => $fichesMetiersTypes,
        ]);
    }

    public function afficherAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);
        $activites = $this->getActiviteService()->getActivitesByFicheMetierType($fiche);

        return new ViewModel([
            'fiche' => $fiche,
            'activites' => $activites,
        ]);
    }

    public function editerLibelleAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(LibelleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/editer-libelle',['id' => $fiche->getId()],[], true));
        $form->bind($fiche);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
//                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [] , true);
            }
        }

        return new ViewModel([
            'title' => 'Modifier le libellé',
            'form' => $form,
        ]);
    }

    public function editerMissionsPrincipalesAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MissionsPrincipalesForm::class);
//        $form->setAttribute('action', $this->url()->fromRoute('activite/editer',['id' => $activite->getId()],[], true));
        $form->bind($fiche);
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getFicheMetierService()->updateFicheMetierType($fiche);
                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [] , true);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function ajouterNouvelleActiviteAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        $activite = new Activite();

        /** @var MissionsPrincipalesForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);
        $form->bind($activite);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getActiviteService()->create($activite);

                $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);

                $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $fiche->getId()], [], true);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);

    }

    public function ajouterActiviteExistanteAction()
    {
        $fiche = $this->getFicheMetierService()->getRequestedFicheMetierType($this, 'id', true);

        /** @var ActiviteExistanteForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(ActiviteExistanteForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier-type/ajouter-activite-existante', ['id' => $fiche->getId()], [], true));
        $form->bind($fiche);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

//            if ($form->isValid()) {
                $activite = $this->getActiviteService()->getActivite($data['activite']);
                $this->getActiviteService()->createFicheMetierTypeActivite($fiche, $activite);
//            }
        }

        return new ViewModel([
            'title' => 'Ajouter une activité existante',
            'form' => $form,
        ]);
    }

    public function retirerActiviteAction()
    {
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        $this->getActiviteService()->removeFicheMetierTypeActivite($couple);

        $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $couple->getFiche()->getId()], [], true);
    }

    public function deplacerActiviteAction()
    {
        $direction = $this->params()->fromRoute('direction');
        $coupleId = $this->params()->fromRoute('id');
        $couple = $this->getActiviteService()->getFicheMetierTypeActivite($coupleId);

        if ($direction === 'up')    $this->getActiviteService()->moveUp($couple);
        if ($direction === 'down')  $this->getActiviteService()->moveDown($couple);

        $this->getActiviteService()->updateFicheMetierTypeActivite($couple);

        $this->redirect()->toRoute('fiche-metier-type/afficher', ['id' => $couple->getFiche()->getId()], [], true);
    }
}