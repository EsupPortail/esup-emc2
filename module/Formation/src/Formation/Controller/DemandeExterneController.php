<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Formation;
use Formation\Form\Demande2Formation\Demande2FormationFormAwareTrait;
use Formation\Form\DemandeExterne\DemandeExterneFormAwareTrait;
use Formation\Form\Justification\JustificationFormAwareTrait;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\FichierNature\FichierNature;
use Formation\Provider\Parametre\FormationParametres;
use Formation\Provider\Validation\DemandeExterneValidations;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\Notification\NotificationServiceAwareTrait;
use Laminas\Form\Element\Select;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */

class DemandeExterneController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FichierServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use NatureServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;

    use DemandeExterneFormAwareTrait;
    use Demande2FormationFormAwareTrait;
    use JustificationFormAwareTrait;
    use SelectionAgentFormAwareTrait;
    use UploadFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $params = [
            'agent' => $this->getAgentService()->getAgent($fromQueries['agent-filtre']['id']??null),
            'organisme' => $fromQueries['organisme']['id']??null,
            'etat' => $this->getEtatTypeService()->getEtatType((isset($fromQueries['etat']) && trim($fromQueries['etat'])!=='')?trim($fromQueries['etat']):null),
            'historise' => $fromQueries['historise']??null,
            'annee' =>  $fromQueries['annee']??null,
        ];

        $demandes = $this->getDemandeExterneService()->getDemandesExternesWithFiltre($params);

        $etats = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(DemandeExterneEtats::TYPE);

        return new ViewModel([
            'demandes' => $demandes,
            'etats' => $etats,

            'params' => $params,
            'plafond' => $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::DEMANDE_EXTERNE_PLAFOND),
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

    public function creerPourAgentAction(): ViewModel|Response
    {
        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/creer-pour-agent', [], [], true));

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $data = $request->getPost();
            $agentId = $data['agent']['id'];
            $agent = $this->getAgentService()->getAgent($agentId);

            if ($agent) return $this->redirect()->toRoute('formation/demande-externe/ajouter', ['agent' => $agent->getId()] ,[], true);
        }

        $vm = new ViewModel([
            'title' => "Sélectionner un agent",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
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
                $this->getDemandeExterneService()->create($demande);
                $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_CREATION_EN_COURS);
                $this->getDemandeExterneService()->update($demande);

                $vm = new ViewModel([
                    'title' => "Ajout d'une demande de formation externe",
                    'agent' => $agent,
                    'form' => $form,
                    'js' => "$(function() { $('.modal').modal('hide'); window.location.reload();})",
                ]);
                $vm->setTemplate('formation/demande-externe/modifier');
                return $vm;
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
            $vm->setTemplate('default/confirmation');
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
            $validation = $demande->getValidationActiveByTypeCode(DemandeExterneValidations::FORMATION_DEMANDE_AGENT);
            if ($validation === null) {
                if ($data["reponse"] === "oui") {
                    $this->getValidationInstanceService()->setValidationActive($demande,DemandeExterneValidations::FORMATION_DEMANDE_AGENT);
                    $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_VALIDATION_AGENT);
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
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-responsable', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('RESPONSABLE');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_RESPONSABLE);
                $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_VALIDATION_RESP);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Validation effectuée.");
                $this->getNotificationService()->triggerValidationResponsableAgent($demande);
                $this->getNotificationService()->triggerValidationResponsableDrh($demande);
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
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/refuser-responsable', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('REFUS');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_REFUS);
                $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_REJETEE);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                $this->getNotificationService()->triggerRefus($demande);
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

    public function validerGestionnaireAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_VALIDATION_DRH);
        $this->getDemandeExterneService()->update($demande);
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-gestionnaire', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('GESTIONNAIRE');
        $form->get('justification')->setValue('Validation du bureau de gestion des formations');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_DRH);
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

    public function refuserGestionnaireAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);

        $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_VALIDATION_DRH);
        $this->getDemandeExterneService()->update($demande);
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-gestionnaire', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('REFUS');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_REFUS);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Refus effectuée.");
                $this->getNotificationService()->triggerRefus($demande);
            }
        }

        $vm =  new ViewModel([
            'title' => "Refus de la demande de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;
    }


    public function validerDrhAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/valider-drh', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('DRH');
        $form->get('justification')->setValue('Validation de la Direction des Ressources Humaines');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_DRH);
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

        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/refuser-drh', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('REFUS');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getValidationInstanceService()->setValidationActive($demande, DemandeExterneValidations::FORMATION_DEMANDE_REFUS);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Refus effectué.");
                $this->getNotificationService()->triggerRefus($demande);
            }
        }

        $vm =  new ViewModel([
            'title' => "Refus de la demande de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
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
            'etat' => InscriptionEtats::ETAT_VALIDER_RESPONSABLE,
            'historise' => '0',
            'annee' => Formation::getAnnee(),
        ];
        $demandesInternes = $this->getDemandeExterneService()->getInscriptionService()->getInscriptionsWithFiltre($paramsInternes);

        $plafond = $this->getParametreService()->getValeurForParametre(FormationParametres::TYPE, FormationParametres::DEMANDE_EXTERNE_PLAFOND);
        $paramsExternes_standard = [
            'etat' => $this->getEtatTypeService()->getEtatTypeByCode(DemandeExterneEtats::ETAT_VALIDATION_RESP),
            'historise' => '0',
            'annee' => Formation::getAnnee(),
        ];
        $demandesExternes_standard = $this->getDemandeExterneService()->getDemandesExternesWithFiltre($paramsExternes_standard);
        $demandesExternes_standard = array_filter($demandesExternes_standard, function (DemandeExterne $a) use ($plafond) { return $a->getMontant() >= $plafond; });

        $paramsExternes_forcee = [
            'etat' => $this->getEtatTypeService()->getEtatTypeByCode(DemandeExterneEtats::ETAT_FORCEE_PARAPHEUR),
            'historise' => '0',
            'annee' => Formation::getAnnee(),
        ];
        $demandesExternes_forcee = $this->getDemandeExterneService()->getDemandesExternesWithFiltre($paramsExternes_forcee);
        $demandesExternes_forcee = array_filter($demandesExternes_forcee, function (DemandeExterne $a) use ($plafond) { return $a->getMontant() >= $plafond; });

        $demandesExternes = array_merge($demandesExternes_standard, $demandesExternes_forcee);

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
            $groupe = (isset($data['groupe']) AND $data['groupe'] !== '')?$this->getFormationGroupeService()->getFormationGroupe($data['groupe']):null;
            //todo HYDRATOR ...
            if  ($libelle AND $volume AND $suivi) {
                $this->getDemandeExterneService()->transformer($demande, $libelle, $groupe, $volume, $suivi);
                $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_TERMINEE);
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

    public function ajouterDevisAction(): ViewModel|Response
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $retour = $this->params()->fromQuery('retour');

        $fichier = new Fichier();
        $devisNature = $this->getNatureService()->getNatureByCode(FichierNature::DEMANDEEXTERNE_DEVIS);
        $fichier->setNature($devisNature);
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/ajouter-devis', ['demande-externe' => $demande->getId()], ['query' => ['retour' => $retour]], true));
        /** @var Select $select */
        $select = $form->get('nature');
        $select->setValueOptions([$devisNature->getId() => $devisNature->getLibelle()]);
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

            if ($retour) return $this->redirect()->toUrl($retour);
            return $this->redirect()->toRoute('inscription-externe', [], [], true);
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

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);

        return $this->redirect()->toRoute('inscription-externe', [], ['fragment' => "demandes"], true);
    }

    /** ENVOYER DANS LE PARAPHEUR *************************************************************************************/

    //todo ajouter justification
    public function envoyerParapheurAction() : ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $form = $this->getJustificationForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/envoyer-parapheur', ['demande-externe' => $demande->getId()], [], true));
        $form->bind($demande);
        $form->get('etape')->setValue('GESTIONNAIRE');
        $form->get('justification')->setValue('Validation');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEtatInstanceService()->setEtatActif($demande, DemandeExterneEtats::ETAT_FORCEE_PARAPHEUR);
                $this->getDemandeExterneService()->update($demande);
                $this->flashMessenger()->addSuccessMessage("Envoi au parapheur effectué.");
                $this->getNotificationService()->triggerValidationDrh($demande);
                $this->getNotificationService()->triggerValidationComplete($demande);
            }
        }

        $vm =  new ViewModel([
            'title' => "Envoi au parapheur de la demande de ". $demande->getAgent()->getDenomination() ." à la formation ".$demande->getLibelle(),
            'inscription' => $demande,
            'form' => $form,
        ]);
        $vm->setTemplate('formation/formation-instance-inscrit/inscription');
        return $vm;

    }

    /** FONCTIONS POUR LE FILTRE **************************************************************************************/

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