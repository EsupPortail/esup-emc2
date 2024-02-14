<?php

namespace Formation\Controller;

use Formation\Entity\Db\Formateur;
use Formation\Form\Formateur\FormateurFormAwareTrait;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;

class FormateurController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormateurServiceAwareTrait;
    use FormateurFormAwareTrait;
    use FormateurFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $formateurs = $this->getFormateurService()->getFormateursWithFiltre($params);
        return new ViewModel([
            'formateurs' => $formateurs,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        return new ViewModel([
            'formateur' => $formateur,
        ]);
    }

    public function ajouterAction(): ViewModel
    {

        $formateur = new Formateur();

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/ajouter', [], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->create($formateur);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Ajout d'un·e formateur·trice",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction(): ViewModel
    {

        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/formateur/modifier', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->update($formateur);
                exit();
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Modification d'un·e formateur·trice",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->historise($formateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/formateur',[],[],true);
    }

    public function restaurerAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->restore($formateur);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('formation/formateur',[],[],true);
    }

    public function supprimerAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormateurService()->delete($formateur);
            exit();
        }

        $vm = new ViewModel();
        if ($formateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du formateur de formation du [" . $formateur->getDenomination() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/formateur/supprimer', ["formateur" => $formateur->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** DEPUIS UNE SESSION A REPENSER **/
    public function ajouterFormateurAction(): ViewModel
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);

        $formateur = new Formateur();
        $formateur->setInstance($instance);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/ajouter-formateur', ['formation-instance' => $instance->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->create($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Ajout d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFormateurAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        $form = $this->getFormateurForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/modifier-formateur', ['formateur' => $formateur->getId()], [], true));
        $form->bind($formateur);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormateurService()->update($formateur);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/formateur/modifier');
        $vm->setVariables([
            'title' => "Modification d'un formateur de formation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserFormateurAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->historise($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function restaurerFormateurAction(): Response
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);
        $this->getFormateurService()->restore($formateur);
        return $this->redirect()->toRoute('formation-instance/afficher', ['formation-instance' => $formateur->getInstance()->getId()], [], true);
    }

    public function supprimerFormateurAction(): ViewModel
    {
        $formateur = $this->getFormateurService()->getRequestedFormateur($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFormateurService()->delete($formateur);
            exit();
        }

        $vm = new ViewModel();
        if ($formateur !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du formateur de formation du [" . $formateur->getPrenom() . " " . $formateur->getNom() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation-instance/supprimer-formateur', ["formateur" => $formateur->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Fonctions de rcherche *****************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $formateurs = $this->getFormateurService()->getFormateursByTerm($term);
            $result = $this->getFormateurService()->formatFormateursJSON($formateurs);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherRattachementAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $rattachements = $this->getFormateurService()->getRattachementByTerm($term);
            $result = $this->getFormateurService()->formatRattachementsJSON($rattachements);
            return new JsonModel($result);
        }
        exit;
    }
}