<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Provider\Observation\EntretienProfessionnelObservations;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenObservation\Entity\Db\ObservationInstance;
use UnicaenObservation\Form\ObservationInstance\ObservationInstanceFormAwareTrait;
use UnicaenObservation\Service\ObservationInstance\ObservationInstanceServiceAwareTrait;
use UnicaenObservation\Service\ObservationType\ObservationTypeServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

class ObservationController extends AbstractActionController
{
    use EntretienProfessionnelServiceAwareTrait;
    use ObservationInstanceServiceAwareTrait;
    use ObservationTypeServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;
    use ObservationInstanceFormAwareTrait;


    public function ajouterAgentAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);
        $type = $this->params()->fromRoute('type');

        $observationType = null;
        if (in_array($type, [EntretienProfessionnelObservations::OBSERVATION_AGENT_ENTRETIEN, EntretienProfessionnelObservations::OBSERVATION_AGENT_PERSPECTIVE, EntretienProfessionnelObservations::OBSERVATION_AGENT_FORMATION, EntretienProfessionnelObservations::OBSERVATION_AGENT_FINALE])) {
            $observationType = $this->getObservationTypeService()->getObservationTypeByCode($type);
        }
        if ($observationType === null) throw new RuntimeException("Type de validation non valide.");

        $observation = new ObservationInstance();
        $observation->setType($observationType);
        $form = $this->getObservationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observation/agent/ajouter', ['entretien-professionnel' => $entretien->getId(), 'type' => $type], [], true));
        $form->bind($observation);
        $form->cacherType();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationInstanceService()->create($observation);
                $entretien->addObservation($observation);
                $this->getEntretienProfessionnelService()->update($entretien);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une ".strtolower($observationType->getLibelle()),
            'form' => $form,
            'js' => " $('.hidden').parent().hide()",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** Observation Autorite ******************************************************************************************/

    public function ajouterAutoriteAction(): ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $observationType = $this->getObservationTypeService()->getObservationTypeByCode(EntretienProfessionnelObservations::OBSERVATION_AUTORITE);

        $observation = new ObservationInstance();
        $observation->setType($observationType);
        $form = $this->getObservationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/observation/autorite/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->cacherType();
        $form->bind($observation);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationInstanceService()->create($observation);
                $entretien->addObservation($observation);
                $this->getEntretienProfessionnelService()->update($entretien);
                exit();
            }
        }

        $vm = new ViewModel([
//            'title' => "Ajout d'une observation sur l'entretien professionnel (en tant qu'autorité hiérarchique)",
            'title' => "Ajout  d'une ".strtolower($observationType->getLibelle()),
            'form' => $form,
            'js' => " $('.hidden').parent().hide()",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

}