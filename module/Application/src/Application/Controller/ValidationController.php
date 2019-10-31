<?php

namespace Application\Controller;

use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\Validation;
use Application\Entity\Db\ValidationDemande;
use Application\Entity\Db\ValidationType;
use Application\Form\Validation\ValidateurFormAwareTrait;
use Application\Form\ValidationDemande\ValidationDemandeFormAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Application\Service\Validation\ValidationDemandeServiceAwareTrait;
use Application\Service\Validation\ValidationServiceAwareTrait;
use Application\Service\Validation\ValidationTypeServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ValidationController extends AbstractActionController {
    use DomaineServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use ValidateurFormAwareTrait;
    use ValidationServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use ValidationDemandeServiceAwareTrait;
    use UserServiceAwareTrait;
    use MailingServiceAwareTrait;

    use ValidationDemandeFormAwareTrait;

    public function indexAction()
    {
        $demandes = $this->getValidationDemandeService()->getValidationsDemandes();

//        $ficheMetiers = [];
//        $domaine = null;
//
//        $domaineId = $this->params()->fromQuery('domaine');
//        if ($domaineId !== null) {
//            $domaine = $this->getDomaineService()->getDomaine($domaineId);
//            $ficheMetiers = $this->getFicheMetierService()->getFicheByDomaine($domaine);
//        } else {
//            $ficheMetiers = $this->getFicheMetierService()->getFichesMetiers();
//        }

        return new ViewModel([
//            'domaine' => $domaine,
//            'ficheMetiers' => $ficheMetiers,
            'em' => $this->getValidationDemandeService()->getEntityManager(),
            'demandes' => $demandes,
        ]);
    }

    public function afficherAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this);
        return new ViewModel([
            'title' => "Affichage de la validation #" . $validation->getId(),
            'validation' => $validation,
        ]);
    }

    public function creerAction()
    {
        $typeCode = $this->params()->fromRoute('type');
        $objectId = $this->params()->fromRoute('objectId');
        $demandeId = $this->params()->fromQuery('demande');

        $query = [];
        if ($demandeId !== null) $query = ["query" => ["demande" => $demandeId]];
        $type = $this->getValidationTypeService()->getValidationTypebyCode($typeCode);

        $validation = new Validation();
        $validation->setType($type);
        $validation->setObjectId($objectId);

        $form = $this->getValidationForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/creer', ['type' => $type->getCode(), 'objectId' => $objectId], $query, true));
        $form->bind($validation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationService()->create($validation);


                $demande = $this->getValidationDemandeService()->getValidationDemande($demandeId);
                if ($demande !== null) {
                    $demande->setValidation($validation);
                    $this->getValidationDemandeService()->update($demande);
                }
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Création d'une validation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $validation = $this->getValidationService()->getRequestedValidation($this);

        $form = $this->getValidationForm();
        $form->setAttribute('action', $this->url()->fromRoute('validation/modifier', ['validation' => $validation->getId()], [], true));
        $form->bind($validation);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationService()->update($validation);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une validation",
            'form' => $form,
        ]);
        return $vm;
    }

    public function creerDemandesFicheMetierDomaineAction() {
        $cibles = $this->getDomaineService()->getDomainesAsOptions();
        $demande = new ValidationDemande();
        $form = $this->getValidationDemandeForm();
        $form->setCibles($cibles);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('validation/creer-demandes-fiche-metier-domaine', [], [], true));
        $form->bind($demande);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $validateur = $demande->getValidateur();
                $domaine = $this->getDomaineService()->getDomaine($demande->getObjectId());
                $demandes = $this->getValidationDemandeService()->creerDemandesFicheMetierDomaine($validateur, $domaine);

                $message  =  count($demandes) . " demandes de validation viennent d'être créées : <ul>";
                foreach ($demandes as $demande) {
                    $titre = $this->getFicheMetierService()->getFicheMetier($demande->getObjectId())->getMetier()->getLibelle();
                    $message .= "<li>#" . $demande->getId() . " - " . $titre . "</li>";
                }
                $message .= "</ul>";
                $this->flashMessenger()->addSuccessMessage($message);
                $this->getMailingService()->notificationDemandesValidations($demande->getValidateur(), $demandes);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification de la demande #".$demande->getId(),
            'form' => $form,
        ]);
        return $vm;
    }

    public function creerDemandeFicheMetierAction() {
        $cibles = $this->getFicheMetierService()->getFichesMetiersAsOptions();
        $demande = new ValidationDemande();
        $form = $this->getValidationDemandeForm();
        $form->setCibles($cibles);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('validation/creer-demande-fiche-metier', [], [], true));
        $form->bind($demande);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $demande->setType($this->getValidationTypeService()->getValidationTypebyCode(ValidationType::FICHE_METIER_RELECTURE));
                $demande->setEntity(FicheMetier::class);
                $this->getValidationDemandeService()->create($demande);

                $titre    = $this->getFicheMetierService()->getFicheMetier($demande->getObjectId())->getMetier()->getLibelle();
                $message  =  "Une demande de validation vient d'être créée : <ul><li>#".$demande->getId()." - ".$titre."</li></ul>";
                $this->flashMessenger()->addSuccessMessage($message);
                $this->getMailingService()->notificationDemandesValidations($demande->getValidateur(), [ $demande ]);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification de la demande #".$demande->getId(),
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierDemandeAction() {
        $cibles = $this->getFicheMetierService()->getFichesMetiersAsOptions();
        $demande = $this->getValidationDemandeService()->getRequestedDemandeValidation($this);
        $form = $this->getValidationDemandeForm();
        $form->setCibles($cibles);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('validation/modifier-demande', ['demande' => $demande->getId()], [], true));
        $form->bind($demande);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationDemandeService()->update($demande);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification de la demande #".$demande->getId(),
            'form' => $form,
        ]);
        return $vm;
    }

    public function detruireDemandeAction() {
        $demande = $this->getValidationDemandeService()->getRequestedDemandeValidation($this);
        $this->getValidationDemandeService()->delete($demande);
        return $this->redirect()->toRoute('validation', [], [], true);
    }
}