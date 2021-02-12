<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationServiceAwareTrait;
use Application\Service\RendererAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Doctrine\ORM\ORMException;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use EntretienProfessionnel\Form\Observation\ObservationFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Export\EntretienProfessionnel\EntretienProfessionnelPdfExporter;
use EntretienProfessionnel\Service\Observation\ObservationServiceAwareTrait;
use Mailing\Service\Mailing\MailingServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EntretienProfessionnelController extends AbstractActionController
{
    use DateTimeAwareTrait;
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EtatServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use ObservationServiceAwareTrait;
    use MailingServiceAwareTrait;
    use ParcoursDeFormationServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use StructureServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;
    use CampagneFormAwareTrait;
    use ObservationFormAwareTrait;

    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    use RendererAwareTrait;

    public function indexAction()
    {
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels();
        $campagnes = $this->getCampagneService()->getCampagnes();

        return new ViewModel([
            'entretiens' => $entretiens,
            'campagnes' => $campagnes,
        ]);
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
                $this->getMailingService()->sendMailType("ENTRETIEN_CONVOCATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'user' => $entretien->getAgent()]);
                //return $this->redirect()->toRoute('entretien-professionnel/renseigner', ['entretien' => $entretien->getId()], [], true);
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
        $this->getMailingService()->sendMailType("ENTRETIEN_CONVOCATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'user' => $entretien->getAgent()]);
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
        $validationAgent = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ENTRETIEN_AGENT', $entretien);
        $validationResponsable = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ENTRETIEN_RESPONSABLE', $entretien);
        $validationDrh = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ENTRETIEN_DRH', $entretien);

        $agent = $entretien->getAgent();
        $fichespostes = ($agent) ? $agent->getFiches() : [];
        $fichesmetiers = [];
        foreach ($fichespostes as $ficheposte) {
            $fiches = $ficheposte->getFichesMetiers();
            foreach ($fiches as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }

        return new ViewModel([
            'title'                     => 'Entretien professionnel ' . $entretien->getCampagne()->getAnnee() . ' de ' . $entretien->getAgent()->getDenomination(),
            'entretien'                 => $entretien,
            'validationAgent'           => $validationAgent,
            'validationResponsable'     => $validationResponsable,
            'validationDrh'             => $validationDrh,

            'agent'                     => $agent,
            'fichespostes'              => $fichespostes,
            'fichesmetiers'             => $fichesmetiers
        ]);
    }

    public function renseignerAction()
    {
        /** TODO  revoir ici une seul fiche de poste actives sinon c'est la merde */
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        $agent = $entretien->getAgent();
        $fichespostes = ($agent) ? $agent->getFiches() : [];
        $fichesmetiers = [];
        $parcours = ($fichespostes[0]) ? $this->getParcoursDeFormationService()->generateParcoursArrayFromFichePoste($fichespostes[0]) : null;

        foreach ($fichespostes as $ficheposte) {
            $fiches = $ficheposte->getFichesMetiers();
            foreach ($fiches as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }

        return new ViewModel([
            'entretien' => $entretien,
            'parcours' => $parcours,

            'agent' => $agent,
            'fichespostes' => $fichespostes,
            'fichesmetiers' => $fichesmetiers,
            'connectedUser' => $this->getUserService()->getConnectedUser(),
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

        $validationType = null;
        $elementText = null;

        switch ($type) {
            case 'Agent' :
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("ENTRETIEN_AGENT");
                break;
            case 'Responsable' :
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("ENTRETIEN_RESPONSABLE");
                break;
            case 'DRH' :
                $validationType = $this->getValidationTypeService()->getValidationTypeByCode("ENTRETIEN_DRH");
                break;
        }

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
                switch ($type) {
                    case 'Agent' :
                        $entretien->setValidationAgent($validation);
                        $responsables = $this->getAgentService()->getResponsablesHierarchiques($entretien->getAgent());
                        $array = [];
                        foreach ($responsables as $responsable) $array[] = $responsable->getEmail();
                        $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_AGENT", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'mailing' => $array]);
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_AGENT));
                        $this->getEntretienProfessionnelService()->update($entretien);
                        break;
                    case 'Responsable' :
                        $entretien->setValidationResponsable($validation);
                        $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_RESPONSABLE", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'mailing' => $entretien->getAgent()->getUtilisateur()->getEmail()]);
                        $this->getMailingService()->sendMailType("COMMUNICATION_AGENT_OBSERVATIONS", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'mailing' => $entretien->getAgent()->getUtilisateur()->getEmail()]);
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
                        $this->getEntretienProfessionnelService()->update($entretien);
                        break;
                    case 'DRH' :
                        $entretien->setValidationDRH($validation);
                        $this->getMailingService()->sendMailType("ENTRETIEN_VALIDATION_DRH", ['campagne' => $entretien->getCampagne(), 'entretien' => $entretien, 'mailing' => $entretien->getResponsable()->getEmail()]);
                        $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_HIERARCHIE));
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
        if ($validation->getType()->getCode() === "ENTRETIEN_AGENT") {
            $entity->setValidationAgent(null);
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_VALIDATION_RESPONSABLE));
        }
        if ($validation->getType()->getCode() === "ENTRETIEN_RESPONSABLE") {
            $entity->setValidationResponsable(null);
            $entity->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
        }
        if ($validation->getType()->getCode() === "ENTRETIEN_DRH") {
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

        $exporter = new EntretienProfessionnelPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'entretien' => $entretien,
            'formations' => $formations,
        ]);

        $agent = $entretien->getAgent()->getDenomination();
        $date = $entretien->getDateEntretien()->format('Ymd');
        $filemane = "EMC2" . $this->getDateTime()->format('YmdHis') . "_" . str_replace(" ", "_", $agent) . '_' . $date . '.pdf';
        try {
            $exporter->getMpdf()->SetTitle("Entretien professionnel de " . $agent . " du " . $entretien->getDateEntretien()->format("d/m/Y"));
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors du changement de titre par MPDF.", 0, $e);
        }
        $exporter->export($filemane);
        exit;
    }

    public function accepterEntretienAction() {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien-professionnel');
        $token = $this->params()->fromRoute('token');

        if ($entretien !== null AND $entretien->getToken() === $token) {
            $entretien->setToken(null);
            $entretien->setAcceptation($this->getDateTime());
            $entretien->setEtat($this->getEtatService()->getEtatByCode(EntretienProfessionnel::ETAT_ACCEPTER));
            $this->getEntretienProfessionnelService()->update($entretien);
            $this->getMailingService()->sendMailType("ENTRETIEN_ACCEPTER_AGENT", ['entretien' => $entretien, 'user' => $entretien->getResponsable()]);
        }

        return new ViewModel([
            'entretien' => $entretien,
            'token' => $token,
        ]);
    }


}
