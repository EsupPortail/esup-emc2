<?php

namespace Formation\Controller;

use Formation\Entity\Db\StagiaireExterne;
use Formation\Form\StagiaireExterne\StagiaireExterneFormAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class StagiaireExterneController extends AbstractActionController
{
    use InscriptionServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;
    use StagiaireExterneFormAwareTrait;

    /** CRUD **********************************************************************************************************/
    
    public function indexAction(): ViewModel
    {
        $stagiaires = $this->getStagiaireExterneService()->getStagiaireExternes('nom', 'ASC', true);

        return new ViewModel([
            'stagiaires' => $stagiaires,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);

        return new ViewModel([
            'stagiaire' => $stagiaire,
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $stagiaire = new StagiaireExterne();

        $form = $this->getStagiaireExterneForm();
        $form->setAttribute('action', $this->url()->fromRoute('stagiaire-externe/ajouter', [], [], true));
        $form->bind($stagiaire);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getStagiaireExterneService()->create($stagiaire);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un·e stagiaire externe",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);

        $form = $this->getStagiaireExterneForm();
        $form->setAttribute('action', $this->url()->fromRoute('stagiaire-externe/modifier', ['stagiaire-externe' => $stagiaire->getId()], [], true));
        $form->bind($stagiaire);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getStagiaireExterneService()->update($stagiaire);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'un·e stagiaire externe",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): ViewModel
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getStagiaireExterneService()->historise($stagiaire);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($stagiaire !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Historisation du stagiaire",
                'text' => "L'historisation supprimera de la liste des stagiaires. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('stagiaire-externe/historiser', ["stagiaire-externe" => $stagiaire->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function restaurerAction(): Response
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);
        $this->getStagiaireExterneService()->restore($stagiaire);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('stagiaire-externe',[],[], true);
    }

    public function supprimerAction(): ViewModel
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getStagiaireExterneService()->delete($stagiaire);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($stagiaire !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du stagiaire",
                'text' => "La suppression est définitive. Êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('stagiaire-externe/supprimer', ["stagiaire-externe" => $stagiaire->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function historiqueAction(): ViewModel
    {
        $stagiaire = $this->getStagiaireExterneService()->getRequestedStagiaireExterne($this);
        $inscriptions = $this->getInscriptionService()->getInscriptionsValideesByStagiaires([$stagiaire],null);

        return new ViewModel([
            'title' => "Historique des formations de ".$stagiaire->getDenomination(true),
            'stagiaire' => $stagiaire,
            'inscriptions' => $inscriptions,
        ]);
    }


    /** RECHERCHE *****************************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $stagiaires = $this->getStagiaireExterneService()->getStagiairesExternesByTerm($term);
            $result = $this->getStagiaireExterneService()->formatStagiaireExterneJSON($stagiaires);
            return new JsonModel($result);
        }
        exit;
    }
}