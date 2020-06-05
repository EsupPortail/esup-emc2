<?php

namespace Application\Controller;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Entity\Db\EntretienProfessionnelCampagne;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Application\Service\Export\EntretienProfessionnel\EntretienProfessionnelPdfExporter;
use Application\Service\Structure\StructureServiceAwareTrait;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Doctrine\ORM\ORMException;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Entity\Db\ValidationInstance;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;
use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class EntretienProfessionnelController extends AbstractActionController {
    use DateTimeAwareTrait;
    use AgentServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use EntretienProfessionnelCampagneServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;
    use StructureServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;
    use EntretienProfessionnelCampagneFormAwareTrait;

    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function indexAction()
    {
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels();
        $campagnes = $this->getEntretienProfessionnelCampagneService()->getEntretiensProfessionnelsCampagnes();

        return new ViewModel([
            'entretiens' => $entretiens,
            'campagnes' => $campagnes,
        ]);
    }

    public function creerAction()
    {
        $agentId = $this->params()->fromQuery('agent');
        $agent = $this->getAgentService()->getAgent($agentId);

        $entretien = new EntretienProfessionnel();
        if ($agent) $entretien->setAgent($agent);

        /** @var EntretienProfessionnelForm $form */
        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/creer', [], [], true));
        $form->bind($entretien);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                /** Creation de l'instance de formulaire **/
                $instance = new FormulaireInstance();
                $formulaire = $this->getFormulaireService()->getFormulaire(1);
                $instance->setFormulaire($formulaire);
                $this->getFormulaireInstanceService()->create($instance);
                $entretien->setFormulaireInstance($instance);
                $this->getEntretienProfessionnelService()->create($entretien);

                $previous = $this->getEntretienProfessionnelService()->getPreviousEntretienProfessionnel($entretien);
                $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
                foreach ($recopies as $recopie) {
                    $splits = explode(";",$recopie->getValeur());
                    $this->getFormulaireInstanceService()->recopie($previous->getFormulaireInstance(), $instance, $splits[0], $splits[1]);
                }

                return $this->redirect()->toRoute('entretien-professionnel/modifier', ['entretien' => $entretien->getId()], [], true);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Création d\'un nouvel entretien professionnel',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function ajouterAction()
    {
        $campagne = $this->getEntretienProfessionnelCampagneService()->getRequestedEntretienProfessionnelCampagne($this);
        $agentId = $this->params()->fromQuery('agent');
        $agent = ($agentId)?$this->getAgentService()->getAgent($agentId):null;
        $structureId = $this->params()->fromQuery('structure');
        $structure = ($structureId)?$this->getStructureService()->getStructure($structureId):null;

        $entretien = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByAgentAndCampagne($agent, $campagne);
        if ($entretien === null) $entretien = new EntretienProfessionnel();
        $entretien->setCampagne($campagne);
        $entretien->setAgent($agent);

        /** @var EntretienProfessionnelForm $form */
        $form = $this->getEntretienProfessionnelForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/ajouter', ['campagne' => $campagne->getId()], ["query" => ["structure" => $structureId, "agent" => $agentId]], true));
        $form->bind($entretien);

        //TODO reduire les selects et recherches à l'aide de la structure

        //TODO retour
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                //TODO dans le service ...
                $instance = new FormulaireInstance();
                $formulaire = $this->getFormulaireService()->getFormulaire(1);
                $instance->setFormulaire($formulaire);
                $this->getFormulaireInstanceService()->create($instance);
                $entretien->setFormulaireInstance($instance);
                $this->getEntretienProfessionnelService()->create($entretien);

                $previous = $this->getEntretienProfessionnelService()->getPreviousEntretienProfessionnel($entretien);
                $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
                foreach ($recopies as $recopie) {
                    $splits = explode(";",$recopie->getValeur());
                    $this->getFormulaireInstanceService()->recopie($previous->getFormulaireInstance(), $instance, $splits[0], $splits[1]);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajout d\'un entretien professionnel professionnel',
            'form'  => $form,
        ]);
        return $vm;
    }

    public function afficherAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $validationAgent = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ACCEPTER_ENTRETIEN_AGENT', $entretien);
        $validationResponsable = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ACCEPTER_ENTRETIEN_RESPONSABLE', $entretien);

        $agent = $entretien->getAgent();
        $fichespostes = ($agent)?$agent->getFiches():[];
        $fichesmetiers = [];
        foreach ($fichespostes as $ficheposte) {
            $fiches = $ficheposte->getFichesMetiers();
            foreach ($fiches as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }

        return new ViewModel([
            'title' => 'Entretien professionnel '.$entretien->getCampagne()->getAnnee().' de '.$entretien->getAgent()->getDenomination(),
            'entretien' => $entretien,
            'validationAgent' => $validationAgent,
            'validationResponsable' => $validationResponsable,

            'agent' => $agent,
            'fichespostes' => $fichespostes,
            'fichesmetiers' => $fichesmetiers
        ]);
    }

    public function modifierAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        $agent = $entretien->getAgent();
        $fichespostes = ($agent)?$agent->getFiches():[];
        $fichesmetiers = [];
        foreach ($fichespostes as $ficheposte) {
            $fiches = $ficheposte->getFichesMetiers();
            foreach ($fiches as $fiche) {
                $fichesmetiers[] = $fiche->getFicheType();
            }
        }

        return new ViewModel([
            'entretien' => $entretien,

            'agent' => $agent,
            'fichespostes' => $fichespostes,
            'fichesmetiers' => $fichesmetiers
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
                'title' => "Suppression de l'entretien professionnel de " . $entretien->getAgent()->getDenomination() . " en date du " .$entretien->getDateEntretien()->format('d/m/Y'),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/detruire', ["entretien" => $entretien->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Validation élement associée à l'agent *************************************************************************/

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
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $validation = null;
            if ($data["reponse"] === "oui") {
                $validation = new ValidationInstance();
                $validation->setType($validationType);
                $validation->setEntity($entretien);
                $this->getValidationInstanceService()->create($validation);
            }
            if ($data["reponse"] === "non") {
                $validation = new ValidationInstance();
                $validation->setType($validationType);
                $validation->setEntity($entretien);
                $validation->setValeur("Refus");
                $this->getValidationInstanceService()->create($validation);
            }
            if ($validation !== null AND $entretien !== null) {
                switch ($type) {
                    case 'Agent' :
                        $entretien->setValidationAgent($validation);
                        break;
                    case 'Responsable' :
                        $entretien->setValidationResponsable($validation);
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
            ]);
        }
        return $vm;
    }

    public function revoquerValidationAction()
    {
        $validation = $this->getValidationInstanceService()->getRequestedValidationInstance($this);
        $this->getValidationInstanceService()->historise($validation);

        /** TODO c'est vraiment crado (faire mieux ...) */
        /** @var EntretienProfessionnel $entity */
        $entity = $this->getValidationInstanceService()->getEntity($validation);

        if ($validation->getType()->getCode() === "ENTRETIEN_AGENT") $entity->setValidationAgent(null);
        if ($validation->getType()->getCode() === "ENTRETIEN_RESPONSABLE") $entity->setValidationResponsable(null);

        try {
            $this->getValidationInstanceService()->getEntityManager()->flush($entity);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.");
        }

        return $this->redirect()->toRoute('entretien-professionnel/modifier', ['entretien' => $entity->getId()], [], true);
    }

    public function exporterAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        $exporter = new EntretienProfessionnelPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'entretien' => $entretien,
        ]);

        $agent = $entretien->getAgent()->getDenomination();
        $date = $entretien->getDateEntretien()->format('Ymd');
        $filemane = "PrEECoG_" . $this->getDateTime()->format('YmdHis') ."_". str_replace(" ","_",$agent).'_'.$date.'.pdf';
        try {
            $exporter->getMpdf()->SetTitle("Entretien professionnel de " . $agent . " du " . $entretien->getDateEntretien()->format("d/m/Y"));
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est surevenu lors du changement de titre par MPDF.", 0 , $e);
        }
        $exporter->export($filemane);
        exit;
    }

    /** CAMPAGNE ******************************************************************************************************/

    public function ajouterCampagneAction()
    {
        $campagne = new EntretienProfessionnelCampagne();
        //TODO set date par defaut

        $form = $this->getEntretienProfessionnelCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/ajouter', [], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEntretienProfessionnelCampagneService()->create($campagne);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierCampagneAction()
    {
        $campagne = $this->getEntretienProfessionnelCampagneService()->getRequestedEntretienProfessionnelCampagne($this);

        $form = $this->getEntretienProfessionnelCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/modifier', ['campagne' => $campagne->getId()], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getEntretienProfessionnelCampagneService()->update($campagne);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserCampagneAction()
    {
        $campagne = $this->getEntretienProfessionnelCampagneService()->getRequestedEntretienProfessionnelCampagne($this);
        $this->getEntretienProfessionnelCampagneService()->historise($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function restaurerCampagneAction()
    {
        $campagne = $this->getEntretienProfessionnelCampagneService()->getRequestedEntretienProfessionnelCampagne($this);
        $this->getEntretienProfessionnelCampagneService()->restore($campagne);
        return $this->redirect()->toRoute('entretien-professionnel', [], ['fragment' => 'campagne'], true);
    }

    public function detruireCampagneAction()
    {
        $campagne = $this->getEntretienProfessionnelCampagneService()->getRequestedEntretienProfessionnelCampagne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getEntretienProfessionnelCampagneService()->delete($campagne);
            exit();
        }

        $vm = new ViewModel();
        if ($campagne !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la campagne " . $campagne->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/detruire', ["campagne" => $campagne->getId()], [], true),
            ]);
        }
        return $vm;
    }
}
