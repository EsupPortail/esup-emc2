<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Formation;
use Formation\Form\Demande2Formation\Demande2FormationFormAwareTrait;
use Formation\Form\DemandeExterne\DemandeExterneFormAwareTrait;
use Formation\Form\Inscription\InscriptionFormAwareTrait;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Validation\DemandeExterneValidations;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class DemandeExterneController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FichierServiceAwareTrait;
    use NatureServiceAwareTrait;
    use NotificationServiceAwareTrait;

    use DemandeExterneFormAwareTrait;
    use Demande2FormationFormAwareTrait;
    use UploadFormAwareTrait;
    use InscriptionFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $params = [
            'agent' => $this->getAgentService()->getAgent($fromQueries['agent']['id']),
            'organisme' => $fromQueries['organisme']['id'],
            'etat' => $this->getEtatService()->getEtat((trim($fromQueries['etat'])!=='')?trim($fromQueries['etat']):null),
            'historise' => $fromQueries['historise'],
            'annee' =>  $fromQueries['annee'],
        ];

        $demandes = $this->getDemandeExterneService()->getDemandesExternesWithFiltre($params);

        $etats = $this->getEtatService()->getEtatsByTypeCode(DemandeExterneEtats::TYPE);

        return new ViewModel([
            'demandes' => $demandes,
            'etats' => $etats,

            'params' => $params
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $agent = $this->getAgentService()->getAgent($demande->getAgent()->getId());

        return new ViewModel([
            "demande" => $demande,
            "agent" => $agent,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $demande = new DemandeExterne();
        $form = $this->getDemandeExterneForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/ajouter', ['agent' => $agent->getId()], [],true));
        $form->bind($demande);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $demande->setAgent($agent);
                $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_CREATION_EN_COURS));
                $this->getDemandeExterneService()->create($demande);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une demande de formation externe",
            'agent' => $agent,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/demande-externe/modifier');
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $form = $this->getDemandeExterneForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/modifier', ['demande-externe' => $demande->getId()], [],true));
        $form->bind($demande);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $this->getDemandeExterneService()->update($demande);
            }
        }

        $vm = new ViewModel([
            'title' => "Modification d'une demande de formation externe",
            'agent' => $demande->getAgent(),
            'form' => $form,
        ]);
        $vm->setTemplate('formation/demande-externe/modifier');
        return $vm;
    }

    public function historiserAction() : Response
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $this->getDemandeExterneService()->historise($demande);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('inscriptions', [], [], true);
    }

    public function restaurerAction() : Response
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $this->getDemandeExterneService()->restore($demande);

        $retour = $this->params()->fromRoute('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('inscriptions', [], [], true);
    }

    public function supprimerAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getDemandeExterneService()->delete($demande);
            exit();
        }

        $vm = new ViewModel();
        if ($demande !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la demande",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('formation/demande-externe/supprimer', ["demande-externe" => $demande->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** VALIDATION ***************************************************************************************/

    public function validerAgentAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = $demande->getValidationByTypeCode(DemandeExterneValidations::FORMATION_DEMANDE_AGENT);
            if ($validation === null) {
                if ($data["reponse"] === "oui") {
                    $this->getDemandeExterneService()->addValidationAgent($demande);
                    $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_VALIDATION_AGENT));
                    $this->getDemandeExterneService()->update($demande);
                    $this->getNotificationService()->triggerValidationAgent($demande);
                }
            }
        }
        $vm = new ViewModel();
        $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
        $vm->setVariables([
            'title' => "Validation de la demande de formation externe",
            'text' => "<div class='alert alert-info'>En validant cette demande de formation externe vous figer cette demande. Un courrier électronique sera envoyé à votre responsable pour la validation de celle-ci. </div>",
            'action' => $this->url()->fromRoute('formation/demande-externe/valider-agent', ["demande-externe" => $demande->getId()], [], true),
            'refus' => false,
        ]);
        return $vm;
    }

    public function validerResponsableAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_VALIDATION_RESP));
        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-responsable', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('HasDescription')->get('description')->setLabel("Motivation obligatoire du responsable de l'agent : ");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($demande->getJustificationResponsable() === null) {
                    $this->flashMessenger()->addErrorMessage("<strong> Échec de la validation </strong> <br/> Veuillez justifier votre validation !");
                } else {
                    $this->getDemandeExterneService()->addValidationResponsable($demande);
                    $this->getDemandeExterneService()->update($demande);
                    $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                    $this->getNotificationService()->triggerValidationResponsableAgent($demande);
                    $this->getNotificationService()->triggerValidationResponsableDrh($demande);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "Validation de la demande de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserResponsableAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_REJETEE));
        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/refuser-responsable', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('HasDescription')->get('description')->setLabel("Motivation obligatoire du refus : ");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($demande->getJustificationRefus() === null) {
                    $this->flashMessenger()->addErrorMessage("<strong> Échec du refus </strong> <br/> Veuillez justifier votre refus !");
                } else {
                    $this->getDemandeExterneService()->addValidationRefus($demande);
                    $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_REJETEE));
                    $this->getDemandeExterneService()->update($demande);
                    $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                    $this->getNotificationService()->triggerRefus($demande);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "refus de l'inscription de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function validerDrhAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_VALIDATION_DRH));
        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-drh', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getDemandeExterneService()->addValidationDrh($demande);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                $this->getNotificationService()->triggerValidationDrh($demande);
                $this->getNotificationService()->triggerValidationComplete($demande);
            }
        }

        $vm =  new ViewModel([
            'title' => "Validation de la demande de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    public function refuserDrhAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_REJETEE));
        $form = $this->getInscriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/refuser-drh', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('HasDescription')->get('description')->setLabel("Motivation obligatoire du refus : ");

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($demande->getJustificationRefus() === null) {
                    $this->flashMessenger()->addErrorMessage("<strong> Échec du refus </strong> <br/> Veuillez justifier votre refus !");
                } else {
                    $this->getDemandeExterneService()->addValidationRefus($demande);
                    $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_REJETEE));
                    $this->getDemandeExterneService()->update($demande);
                    $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                    $this->getNotificationService()->triggerRefus($demande);
                }
            }
        }

        $vm =  new ViewModel([
            'title' => "refus de l'inscription de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }

    /** GESTION DES DEMANDES *************************************************************************/
    public function parapheurAction() : ViewModel
    {
        $paramsInternes = [
            'etat' => $this->getEtatService()->getEtatByCode(InscriptionEtats::ETAT_VALIDER_RESPONSABLE),
            'historise' => '0',
            'annee' => Formation::getAnnee(),
        ];
        $demandesInternes = $this->getDemandeExterneService()->getFormationInstanceInscritService()->getInscriptionsWithFiltre($paramsInternes);

        $paramsExternes = [
            'etat' => $this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_VALIDATION_RESP),
            'historise' => '0',
            'annee' => Formation::getAnnee(),
        ];
        $demandesExternes = $this->getDemandeExterneService()->getDemandesExternesWithFiltre($paramsExternes);

        return new ViewModel([
            'demandesInternes' => $demandesInternes,
            'demandesExternes' => $demandesExternes,
        ]);
    }

    public function gererAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $form = $this->getDemande2formationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/gerer', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;
            $volume = $data['volume']??null;
            $suivi = $data['suivi']??null;
            //todo HYDRATOR ...
            if  ($libelle AND $volume AND $suivi) {
                $session = $this->getDemandeExterneService()->transformer($demande, $libelle, $volume, $suivi);
                $demande->setEtat($this->getEtatService()->getEtatByCode(DemandeExterneEtats::ETAT_TERMINEE));
                $this->getDemandeExterneService()->update($demande);
            }
        }

        $vm =  new ViewModel([
            'title' => "Transformation de la demande en formation",
            'form' => $form,
        ]);
        $vm->setTemplate('formation/default/default-form');
        return $vm;
    }

    /** DEVIS *********************************************************************************************/

    public function ajouterDevisAction()
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $fichier = new Fichier();
        $devisNature = $this->getNatureService()->getNatureByCode('DEMANDEEXTERNE_DEVIS');
        $fichier->setNature($devisNature);
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/ajouter-devis', ['demande-externe' => $demande->getId()], [], true));
        $form->get('nature')->setValueOptions([$devisNature->getId() => $devisNature->getLibelle()]);
        $form->bind($fichier);


        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();
            $file = $files['fichier'];

            if ($file['name'] != '') {
                $nature = $this->getNatureService()->getNature($data['nature']);
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $demande->addDevis($fichier);
                $this->getDemandeExterneService()->update($demande);
            }
            return $this->redirect()->toRoute('inscription-formation', [], [], true);
        }

        $vm = new ViewModel();
        $vm->setTemplate('formation/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerDevisAction() : Response
    {
        $fichier = $this->getFichierService()->getRequestedFichier($this, 'devis');
        $this->getFichierService()->delete($fichier);

        return $this->redirect()->toRoute('inscription-formation', [], ['fragment' => "demandes"], true);
    }

    /** FONCTIONS POUR LE FILTRE ***************************************************************************/

    public function rechercherAgentAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getDemandeExterneService()->findAgentByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherOrganismeAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $organismes = $this->getDemandeExterneService()->findOrganismeByTerm($term);

            $result = [];
            foreach ($organismes as $organisme) {
                $result[] = array(
                    'id' => $organisme,
                    'label' => $organisme,
                );
            }
            usort($result, function ($a, $b) {
                return strcmp($a['label'], $b['label']);
            });

            return new JsonModel($result);
        }
        exit;
    }
}