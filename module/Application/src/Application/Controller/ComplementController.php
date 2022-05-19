<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Complement;
use Application\Entity\Db\Interfaces\HasComplementsInterface;
use Application\Form\Complement\ComplementFormAwareTrait;
use Application\Service\Complement\ComplementServiceAwareTrait;
use Structure\Entity\Db\Structure;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ComplementController extends AbstractActionController {
    use EntityManagerAwareTrait;
    use ComplementServiceAwareTrait;
    use ComplementFormAwareTrait;

    public function afficherAction() : ViewModel
    {
        $complement = $this->getComplementService()->getRequestedComplement($this);

        return new ViewModel([
            'title' => 'Affichage du complement #' . $complement->getId(),
            'complement' => $complement,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $attachmentType = $this->params()->fromRoute('attachmenttype');
        $attachmentId = $this->params()->fromRoute('attachmentid');
        $type = $this->params()->fromRoute('type');

        /** @var HasComplementsInterface $attachment */
        $attachment = $this->getEntityManager()->getRepository($attachmentType)->find($attachmentId);

        $complement = new Complement();
        $complement->setAttachmentType(get_class($attachment));
        $complement->setAttachmentId($attachment->getId());

        $form = $this->getComplementForm();
        $form->setAttribute('action', $this->url()->fromRoute('complement/ajouter', ['attachmenttype' => $attachmentType, 'attachmentid' => $attachmentId, 'type' => $type], [], true));

        $complement->setType($type);
        switch ($type) {
            case Complement::COMPLEMENT_TYPE_RESPONSABLE :
            case Complement::COMPLEMENT_TYPE_AUTORITE :
                $complement->setComplementType(Agent::class);
                /** @see AgentController::rechercherAction() */
                $form->get('sas')->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-large', [], [], true));
                break;
            case Complement::COMPLEMENT_TYPE_STRUCTURE :
                $complement->setComplementType(Structure::class);
                $form->get('sas')->setAutocompleteSource($this->url()->fromRoute('structure/rechercher', [], [], true));
                break;
        }
        $form->bind($complement);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getComplementService()->create($complement);
                $attachment->addComplement($complement);
                $this->getEntityManager()->flush($attachment);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/complement/formulaire');
        $vm->setVariables([
            'title' => "Ajout d'un complement pour [".$attachment->toString()."]",
            'type' => $type,
            'form' => $form,
        ]);
        return $vm;

    }

    public function modifierAction() : ViewModel
    {
        $complement = $this->getComplementService()->getRequestedComplement($this);

        $form = $this->getComplementForm();
        $form->setAttribute('action', $this->url()->fromRoute('complement/modifier', ['complement' => $complement->getId()], [], true));

        /** @var HasComplementsInterface $attachment */
        $attachment = $this->getEntityManager()->getRepository($complement->getAttachmentType())->find($complement->getAttachmentId());

        switch ($complement->getType()) {
            case Complement::COMPLEMENT_TYPE_RESPONSABLE :
            case Complement::COMPLEMENT_TYPE_AUTORITE :
                $form->get('sas')->setAutocompleteSource($this->url()->fromRoute('agent/rechercher', [], [], true));
                break;
            case Complement::COMPLEMENT_TYPE_STRUCTURE :
                $form->get('sas')->setAutocompleteSource($this->url()->fromRoute('structure/rechercher', [], [], true));
                break;
        }
        $form->bind($complement);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getComplementService()->update($complement);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/complement/formulaire');
        $vm->setVariables([
            'title' => "Modification d'un complement pour [".$attachment->toString()."]",
            'form' => $form,
            'type' => $complement->getType(),
        ]);
        return $vm;
    }

    public function supprimerAction() : ViewModel
    {
        $complement = $this->getComplementService()->getRequestedComplement($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getComplementService()->delete($complement);
            exit();
        }

        /** @var HasComplementsInterface $attachment */
        $attachment = $this->getEntityManager()->getRepository($complement->getAttachmentType())->find($complement->getAttachmentId());

        $vm = new ViewModel();
        if ($complement !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du complément #" . $complement->getId() . " pour [".$attachment->toString()."]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('complement/supprimer', ["complement" => $complement->getId()], [], true),
            ]);
        }
        return $vm;
    }
}