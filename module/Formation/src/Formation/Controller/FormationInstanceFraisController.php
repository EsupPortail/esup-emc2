<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationInstanceFrais;
use Formation\Form\FormationInstanceFrais\FormationInstanceFraisFormAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FormationInstanceFraisController extends AbstractActionController {
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use FormationInstanceFraisServiceAwareTrait;
    use FormationInstanceFraisFormAwareTrait;

    public function renseignerFraisAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);
        if ($inscrit->getFrais() === null) {
            $frais = new FormationInstanceFrais();
            $frais->setInscrit($inscrit);
            $this->getFormationInstanceFraisService()->create($frais);
        }
        $frais = $inscrit->getFrais();

        $form = $this->getFormationInstanceFraisForm();
        $form->setAttribute('action', $this->url()->fromRoute('formation-instance/renseigner-frais', ['inscrit' => $inscrit->getId()], [], true));
        $form->bind($frais);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFormationInstanceFraisService()->update($frais);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Saisie des frais de ".$inscrit->getAgent()->getDenomination(),
            'form' => $form,
        ]);
        return $vm;
    }
}