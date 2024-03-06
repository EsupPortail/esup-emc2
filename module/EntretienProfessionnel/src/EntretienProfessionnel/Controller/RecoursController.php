<?php

namespace EntretienProfessionnel\Controller;

use DateTime;
use EntretienProfessionnel\Entity\Db\Recours;
use EntretienProfessionnel\Form\Recours\RecoursFormAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Recours\RecoursServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class RecoursController extends AbstractActionController
{
    use EntretienProfessionnelServiceAwareTrait;
    use RecoursServiceAwareTrait;
    use RecoursFormAwareTrait;

    public function ajouterAction() : ViewModel
    {
        $entretien = $this->getEntretienProfessionnelService()->getRequestedEntretienProfessionnel($this);

        $recours = new Recours();
        $recours->setEntretien($entretien);
        $recours->setDateProcedure(new DateTime());
        $recours->setEntretienModifiable(true);

        $form = $this->getRecoursForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/recours/ajouter', ['entretien-professionnel' => $entretien->getId()], [], true));
        $form->bind($recours);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRecoursService()->create($recours);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une procédure de recours",
            'form' => $form,
            'js' => null,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }
}