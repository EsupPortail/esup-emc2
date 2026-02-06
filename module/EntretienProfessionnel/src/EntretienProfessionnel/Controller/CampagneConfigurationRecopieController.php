<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationRecopie;
use EntretienProfessionnel\Form\CampagneConfigurationRecopie\CampagneConfigurationRecopieFormAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\CampagneConfigurationRecopie\CampagneConfigurationRecopieServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;

class CampagneConfigurationRecopieController extends AbstractActionController
{
    use CampagneServiceAwareTrait;
    use CampagneConfigurationRecopieServiceAwareTrait;
    use CampagneConfigurationRecopieFormAwareTrait;

    /** @method FlashMessenger flashMessenger() */

    public function indexAction(): ViewModel
    {
        $campagneConfigurationRecopies = $this->getCampagneConfigurationRecopieService()->getCampagneConfigurationRecopies(true);
        $log = $this->getCampagneConfigurationRecopieService()->verifierTypes();
        if ($log !== '') $this->flashMessenger()->addWarningMessage($log);

        $vm =  new ViewModel([
            'campagneConfigurationRecopies' => $campagneConfigurationRecopies,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/index-recopies');
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $recopie = new CampagneConfigurationRecopie();
        $form = $this->getCampagneConfigurationRecopieForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/configuration-recopie/ajouter', [], [], true));
        $form->bind($recopie);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneConfigurationRecopieService()->create($recopie);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une recopie",
            'form' => $form,
        ]);
        $vm->setTemplate('entretien-professionnel/configuration/recopie-form');
        return $vm;
    }

    public function verifierAction() : ViewModel
    {

    }

}
