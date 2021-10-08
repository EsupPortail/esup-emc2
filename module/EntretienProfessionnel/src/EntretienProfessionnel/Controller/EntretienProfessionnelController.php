<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\RendererAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnelConstant;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Form\Observation\ObservationFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Observation\ObservationServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Contenu\ContenuServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class EntretienProfessionnelController extends AbstractActionController
{
    use DateTimeAwareTrait;
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use ObservationServiceAwareTrait;
    use MailingServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use ContenuServiceAwareTrait;
    use UrlServiceAwareTrait;


    use EntretienProfessionnelFormAwareTrait;
    use CampagneFormAwareTrait;
    use ObservationFormAwareTrait;

    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    use RendererAwareTrait;

    public function indexAction()
    {
        $fromQueries  = $this->params()->fromQuery();
        $agentId      = $fromQueries['agent'];
        $responsableId = $fromQueries['responsable'];
        $structureId  = $fromQueries['structure'];
        $campagneId   = $fromQueries['campagne'];
        $etatId       = $fromQueries['etat'];
        $agent        = ($agentId !== '')?$this->getAgentService()->getAgent($agentId):null;
        $responsable  = ($responsableId !== '')?$this->getUserService()->getUtilisateur($responsableId):null;
        $structure    = ($structureId !== '')?$this->getStructureService()->getStructure($structureId):null;
        $campagne     = ($campagneId !== null AND $campagneId !== '')?$this->getCampagneService()->getCampagne($campagneId):null;
        $etat         = ($etatId !== null AND  $etatId !== '')?$this->getEtatService()->getEtat($etatId):null;

        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels($agent, $responsable, $structure, $campagne, $etat);
        
        $campagnes = $this->getCampagneService()->getCampagnes();
        $type = $this->getEtatTypeService()->getEtatTypeByCode('ENTRETIEN_PROFESSIONNEL');
        $etats = $this->getEtatService()->getEtatsByType($type);

        return new ViewModel([
            'entretiens' => $entretiens,
            'campagnes' => $campagnes,
            'etats' => $etats,

            'params' => [
                'campagneId' => $campagneId,
                'etatId' => $etatId,
                'agent' => $agent,
                'responsable' => $responsable,
                'structure' => $structure,
            ],
        ]);
    }

    public function rechercherResponsableAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $responsables = $this->getEntretienProfessionnelService()->findResponsableByTerm($term);
            $result = $this->getUserService()->formatUserJSON($responsables);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherAgentAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getEntretienProfessionnelService()->findAgentByTerm($term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherStructureAction()
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $structures = $this->getEntretienProfessionnelService()->findStructureByTerm($term);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    /** Gestion des entretiens professionnels *************************************************************************/

    public function creerAction()
    {
        // From route
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        // From Query
        $agentId = $this->params()->fromQuery('agent');
        $agent = ($agentId) ? $this->getAgentService()->getAgent($agentId) : null;
        $structureId = $this->params()->fromQuery('structure');
        $structure = ($structureId) ? $this->getStructureService()->getStructure($structureId) : null;

        // ne pas dupliquer les entretiens (si il existe alors on l'affiche)
        $entretien = null;
        if ($campagne !== null and $agent !== null) $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent, $campagne);
        if ($entretien !== null) {
            /** @see EntretienProfessionnelController::afficherAction() */
            return $this->redirect()->toRoute('entretien-professionnel/afficher', ["entretien" => $entretien->getId()], [], true);
        }

        $entretien = new EntretienProfessionnel();
        if ($campagne !== null) $entretien->setCampagne($campagne);
        if ($agent !== null) $entretien->setAgent($agent);

        $form = $this->getEntretienProfessionnelForm();
        if ($campagne !== null) {
            $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', ["campagne" => $campagne->getId()], ["query" => ["agent" => $agentId, "structure" => $structureId]], true));
        } else {
            $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', [], ["query" => ["agent" => $agentId, "structure" => $structureId]], true));
        }
        $form->bind($entretien);

        if ($structure !== null) {
            /** @var SearchAndSelect $element */
            $element = $form->get('responsable');
            $element->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-gestionnaires', ['structure' => $structure->getId()], [], true));
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $entretien_instance = $this->getFormulaireInstanceService()->createInstance('ENTRETIEN_PROFESSIONNEL');
                $formation_instance = $this->getFormulaireInstanceService()->createInstance('FORMATION');
                $entretien->setFormulaireInstance($entretien_instance);
                $entretien->setFormationInstance($formation_instance);
                $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTATION));
                $this->getEntretienProfessionnelService()->create($entretien);
                $this->getEntretienProfessionnelService()->recopiePrecedent($entretien);
                $url = $this->getUrlService();
                $url->setVariables(['entretien' => $entretien]);
                $mail = $this->getMailingService()->sendMailType("ENTRETIEN_CONVOCATION_ENVOI", [
                        'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $url, 'user' => $entretien->getAgent()]);
                $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un nouvel entretien professionnel',
            'form' => $form,
        ]);
        return $vm;
    }

    public function envoyerConvocationAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $mail = $this->getMailingService()->sendMailType("ENTRETIEN_CONVOCATION_ENVOI", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'user' => $entretien->getAgent()]);
        $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function modifierAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $agent = $entretien->getAgent();
        if ($structure === null) $structure = $agent->getAffectationPrincipale()->getStructure();

        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/modifier', ['entretien' => $entretien->getId()], [], true));
        $form->bind($entretien);
        /** @var SearchAndSelect $element */
        $element = $form->get('responsable');
        $element->setAutocompleteSource($this->url()->fromRoute('structure/rechercher-gestionnaires', ['structure' => $structure->getId()], [], true));
        $element = $form->get('agent');
        $element->setAttribute('readonly', true);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEntretienProfessionnelService()->update($entretien);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modification d\'un entretien professionnel professionnel',
            'form' => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $agent = $this->getAgentService()->getAgent($entretien->getAgent()->getId());
        $mails = $this->getMailingService()->getMailsByAttachement(EntretienProfessionnel::class, $entretien->getId());

        $fichesposte = ($agent) ? $agent->getFichePosteActif() : [];
        $fichesmetiers = [];
        if ($fichesposte) {
            foreach ($fichesposte->getFichesMetiers() as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }
        $parcours = ($fichesposte) ? $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fichesposte) : null;

        return new ViewModel([
            'title'                     => 'Entretien professionnel ' . $entretien->getCampagne()->getAnnee() . ' de ' . $entretien->getAgent()->getDenomination(),
            'entretien'                 => $entretien,

            'agent'                     => $agent,
            'fichesposte'               => $fichesposte,
            'fichesmetiers'             => $fichesmetiers,
            'parcours'                  => $parcours,
            'mails'                     => $mails,
            'documents'                 => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),
        ]);
    }

    public function renseignerAction()
    {
        /** TODO  revoir ici une seul fiche de poste actives sinon c'est la merde */
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $agent = $this->getAgentService()->getAgent($entretien->getAgent()->getId());
        $ficheposte = ($agent) ? $agent->getFichePosteActif() : null;
        $fichesmetiers = [];
        $parcours = ($ficheposte) ? $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($ficheposte) : null;
        $mails = $this->getMailingService()->getMailsByAttachement(EntretienProfessionnel::class, $entretien->getId());

        $fiches = ($ficheposte)?$ficheposte->getFichesMetiers():[];
        foreach ($fiches as $fiche) {
            $fichesmetiers[] = $fiche->getFicheType();
        }

        return new ViewModel([
            'entretien' => $entretien,
            'parcours' => $parcours,

            'agent' => $agent,
            'ficheposte' => $agent->getFichePosteActif(),
            'fichesmetiers' => $fichesmetiers,
            'connectedUser' => $this->getUserService()->getConnectedUser(),
            'mails'                     => $mails,
            'documents'                 => $this->getEntretienProfessionnelService()->getDocumentsUtiles(),
        ]);
    }

    public function historiserAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->historise($entretien);
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function restaurerAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $this->getEntretienProfessionnelService()->restore($entretien);
        return $this->redirect()->toRoute('entretien-professionnel', [], [], true);
    }

    public function detruireAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEntretienProfessionnelService()->delete($entretien);
            exit();
        }

        $vm = new ViewModel();
        if ($entretien !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de l'entretien professionnel de " . $entretien->getAgent()->getDenomination() . " en date du " . $entretien->getDateEntretien()->format('d/m/Y'),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/detruire', ["entretien" => $entretien->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Validation élément associée à l'agent *************************************************************************/

    public function validerElementAction()
    {
        $type = $this->params()->fromRoute('type');
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $entityId = $entretien->getId();

        $validationType = $this->getValidationTypeService()->getValidationTypeByCode($type);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = null;
            if ($data["reponse"] === "oui") {
                $validation = $this->getValidationInstanceService()->createValidation($validationType, $entretien);
            }
            if ($data["reponse"] === "non") {
                $validation = $this->getValidationInstanceService()->createValidation($validationType, $entretien, "Refus");
            }
            if ($validation !== null and $entretien !== null) {
                $urlService = $this->getUrlService();
                $urlService->setVariables(['entretien' => $entretien]);
                switch ($type) {
                    case EntretienProfessionnelConstant::VALIDATION_AGENT :
                        $entretien->setValidationAgent($validation);
                        $responsables = $this->getAgentService()->getResponsablesHierarchiques($entretien->getAgent());
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_AGENT));
                        $mail = $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'agent' => $entretien->getAgent(), 'user' => $responsables, 'UrlService' => $urlService]);
                        $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
                        $this->getEntretienProfessionnelService()->update($entretien);
                        $mail = $this->getMailingService()->sendMailType("ENTRETIEN_OBSERVATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien,  'agent' => $entretien->getAgent(), 'UrlService' => $urlService, 'mailing' => $entretien->getResponsable()->getEmail()]);
                        $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
                        $this->getEntretienProfessionnelService()->update($entretien);
                        break;
                    case EntretienProfessionnelConstant::VALIDATION_RESPONSABLE :
                        $entretien->setValidationResponsable($validation);
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
                        $mail1 = $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_RESPONSABLE", ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $urlService, 'mailing' => $entretien->getAgent()->getEmail()]);
                        $this->getMailingService()->addAttachement($mail1, EntretienProfessionnel::class, $entretien->getId());
                        $this->getEntretienProfessionnelService()->update($entretien);
                        break;
                    case EntretienProfessionnelConstant::VALIDATION_DRH :
                        $entretien->setValidationDRH($validation);
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE));
                        $mail = $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_HIERARCHIE", ['agent' => $entretien->getAgent(), 'campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'UrlService' => $urlService, 'mailing' => $entretien->getResponsable()->getEmail()]);
                        $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
                        $this->getEntretienProfessionnelService()->update($entretien);
                        break;
                }
                $this->getEntretienProfessionnelService()->update($entretien);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($entretien !== null) {
            $vm->setTemplate('unicaen-validation/validation-instance/validation-modal');
            $vm->setVariables([
                'title' => "Validation de l'entretien",
                'text' => "Validation de l'entretien",
                'action' => $this->url()->fromRoute('entretien-professionnel/valider-element', ["type" => $type, "entretien" => $entityId], [], true),
                'refus' => false,
            ]);
        }
        return $vm;
    }

    public function revoquerValidationAction()
    {
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);


        /** @var EntretienProfessionnel $entity */
        $entity = $this->getValidationInstanceService()->getEntity($validation);

        /** TODO c'est vraiment crado (faire mieux ...) */
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_AGENT) {
            $entity->setValidationAgent(null);
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_RESPONSABLE) {
            $entity->setValidationResponsable(null);
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
        }
        if ($validation->getType()->getCode() === EntretienProfessionnelConstant::VALIDATION_DRH) {
            $entity->setValidationDRH(null);
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_AGENT));
        }

        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        return $this->redirect()->toRoute('entretien-professionnel/renseigner', ['entretien' => $entity->getId()], ['fragment' => 'validation'], true);
    }

    public function exporterAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $formations = $this->getAgentService()->getFormationsSuiviesByAnnee($entretien->getAgent(), $entretien->getAnnee());

        $contenu = $this->getContenuService()->getContenuByCode('ENTRETIEN_PROFESSIONNEL');
        $vars= [
            'entretien' => $entretien,
            'agent' => $entretien->getAgent(),
            'formations' => $formations,
            'campagne' => $entretien->getCampagne(),
        ];
        $titre = $this->getContenuService()->generateTitre($contenu, $vars);
        $texte = $this->getContenuService()->generateContenu($contenu, $vars);
        $complement = $this->getContenuService()->generateComplement($contenu, $vars);

        $exporter = new PdfExporter();
        $exporter->getMpdf()->SetTitle($titre);
        $exporter->setHeaderScript('');
        $exporter->setFooterScript('');
        $exporter->addBodyHtml($texte);
        return $exporter->export($complement, PdfExporter::DESTINATION_BROWSER, null);
    }

    public function accepterEntretienAction() {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien-professionnel');
        $token = $this->params()->fromRoute('token');

        if ($entretien !== null AND $entretien->getToken() === $token) {
            $entretien->setToken(null);
            $entretien->setAcceptation($this->getDateTime());
            $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
            $this->getEntretienProfessionnelService()->update($entretien);
            $mail = $this->getMailingService()->sendMailType("ENTRETIEN_CONVOCATION_ACCEPTER", ['entretien' => $entretien, 'campagne' => $entretien->getCampagne(), 'agent' => $entretien->getAgent(), 'user' => $entretien->getResponsable()]);
            $this->getMailingService()->addAttachement($mail, EntretienProfessionnel::class, $entretien->getId());
        }

        return new ViewModel([
            'entretien' => $entretien,
            'token' => $token,
        ]);
    }


}
