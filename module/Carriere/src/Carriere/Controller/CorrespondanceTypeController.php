<?php

namespace Carriere\Controller;

use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\CorrespondanceType;
use Carriere\Form\SpecialiteType\SpecialiteTypeFormAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use DateTime;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CorrespondanceTypeController extends AbstractActionController {
    use CorrespondanceServiceAwareTrait;
    use CorrespondanceTypeServiceAwareTrait;
    use SpecialiteTypeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $types = $this->getCorrespondanceTypeService()->getCorrespondancesTypes();

        return new ViewModel([
            'types' => $types
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $type = $this->getCorrespondanceTypeService()->getRequestedCorrespondanceType($this);
        $correspondances = $this->getCorrespondanceService()->getCorrespondancesByType($type);

        return new ViewModel([
            'title' => "Affichage du type de spécialité [".$type->getCode()."]",
            'type' => $type,
            'correspondances' => $correspondances,
        ]);
    }

    public function ajouterAction() : ViewModel
    {
        $correspondanceType = new CorrespondanceType();

        $form = $this->getSpecialiteTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('carriere/correspondance-type/ajouter', [],[],true));
        $form->bind($correspondanceType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCorrespondanceTypeService()->create($correspondanceType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un type de spécialité",
            'form' => $form,
        ]);
        $vm->setTemplate("carriere/correspondance-type/formulaire");
        return $vm;
    }

    public function modifierAction() : ViewModel
    {
        $correspondanceType = $this->getCorrespondanceTypeService()->getRequestedCorrespondanceType($this, 'correspondance-type');

        $form = $this->getSpecialiteTypeForm();
        $form->setAttribute('action', $this->url()->fromRoute('carriere/correspondance-type/modifier', ['correspondance-type' => $correspondanceType?->getId()],[],true));
        $form->bind($correspondanceType);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $correspondanceType->setUpdatedOn(new DateTime());
                $this->getCorrespondanceTypeService()->update($correspondanceType);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter un type de spécialité",
            'form' => $form,
        ]);
        $vm->setTemplate("carriere/correspondance-type/formulaire");
        return $vm;
    }

    public function supprimerAction() : ViewModel
    {
        $correspondanceType = $this->getCorrespondanceTypeService()->getRequestedCorrespondanceType($this, 'correspondance-type');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getCorrespondanceTypeService()->delete($correspondanceType);
            exit();
        }

        $vm = new ViewModel();
        if ($correspondanceType !== null) {

            $warning = null;
            /** @var Correspondance[] $correspondances */
            $correspondances = $correspondanceType->getCorrespondances()->toArray();
            if (!empty($correspondances)) {
                $warning = "<span class='icon icon-attention'></span> Attention, ce type de spécialité est utilisé par ".count($correspondances)." spécialité·s :";
                $warning .= "<ul>";
                foreach ($correspondances as $correspondance) {
                    $warning .= "<li>".$correspondance->getLibelleLong()." (".$correspondanceType->getCode()." ". $correspondance->getCategorie() .")</li>";
                }
                $warning .= "</ul>";
            }

            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'un type de spécialité " . $correspondanceType->getLibelleLong(),
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'warning' => $warning,
                'action' => $this->url()->fromRoute('carriere/correspondance-type/supprimer', ["correspondance-type" => $correspondanceType->getId()], [], true),
            ]);
        }
        return $vm;
    }
}