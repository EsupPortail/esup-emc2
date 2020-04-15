<?php

namespace Application\Controller;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Form\EntretienProfessionnel\EntretienProfessionnelFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Autoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Doctrine\ORM\ORMException;
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
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ValidationTypeServiceAwareTrait;

    use EntretienProfessionnelFormAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use FormulaireServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    public function indexAction()
    {
        $entretiens = $this->getEntretienProfessionnelService()->getEntretiensProfessionnels();

        return new ViewModel([
            'entretiens' => $entretiens,
        ]);
    }

    public function creerAction()
    {
        $entretien = new EntretienProfessionnel();
        $entretien->setAnnee($this->getAnneeScolaire());

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

    public function afficherAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');
        $validationAgent = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ACCEPTER_ENTRETIEN_AGENT', $entretien);
        $validationResponsable = $this->getValidationInstanceService()->getValidationInstanceByCodeAndEntite('ACCEPTER_ENTRETIEN_RESPONSABLE', $entretien);


        return new ViewModel([
            'title' => 'Entretien professionnel '.$entretien->getAnnee().' de '.$entretien->getAgent()->getDenomination(),
            'entretien' => $entretien,
            'validationAgent' => $validationAgent,
            'validationResponsable' => $validationResponsable,
        ]);
    }

    public function modifierAction()
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this, 'entretien');

        return new ViewModel([
            'entretien' => $entretien,
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
}
