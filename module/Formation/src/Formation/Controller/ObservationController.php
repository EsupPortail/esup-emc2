<?php

namespace Formation\Controller;

use Formation\Provider\Observation\FormationObservations;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenObservation\Entity\Db\ObservationInstance;
use UnicaenObservation\Form\ObservationInstance\ObservationInstanceFormAwareTrait;
use UnicaenObservation\Service\ObservationInstance\ObservationInstanceServiceAwareTrait;
use UnicaenObservation\Service\ObservationType\ObservationTypeServiceAwareTrait;

class ObservationController extends AbstractActionController
{
    use DemandeExterneServiceAwareTrait;
    use ObservationInstanceServiceAwareTrait;
    use ObservationTypeServiceAwareTrait;
    use ObservationInstanceFormAwareTrait;


    public function ajouterDemandeExterneAction(): ViewModel
    {
        $demande = $this->getDemandeExterneService()->getRequestedDemandeExterne($this);
        $observationType = $this->getObservationTypeService()->getObservationTypeByCode(FormationObservations::OBSERVATION_DEMANDEEXTERNE_BUREAU);

        $observation = new ObservationInstance();
        $observation->setType($observationType);
        $form = $this->getObservationInstanceForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation/demande-externe/ajouter-observation', ['demande-externe' => $demande->getId(), 'type' => FormationObservations::OBSERVATION_DEMANDEEXTERNE_BUREAU], [], true));
        $form->bind($observation);
        $form->cacherType();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getObservationInstanceService()->create($observation);
                $demande->addObservation($observation);
                $this->getDemandeExterneService()->update($demande);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'".strtolower($observationType->getLibelle()),
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
            'title' => "Ajout  d'".strtolower($observationType->getLibelle()),
            'form' => $form,
            'js' => " $('.hidden').parent().hide()",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

}