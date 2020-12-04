<?php

namespace Application\Controller;

use Application\Entity\Db\FicheProfil;
use Application\Form\FicheProfil\FicheProfilFormAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\FicheProfil\FicheProfilServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenDocument\Service\Exporter\ExporterServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheProfilController extends AbstractActionController {
    use ExporterServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use FicheProfilServiceAwareTrait;
    use StructureServiceAwareTrait;
    use FicheProfilFormAwareTrait;

    public function ajouterAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $fichespostes = $this->getFichePosteService()->getFichesPostesByStructures([$structure], true);

        $ficheprofil = new FicheProfil();
        $ficheprofil->setStructure($structure);

        $form = $this->getFicheProfilForm();
        $form->setStructure($structure);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-profil/ajouter', ['structure' => $structure->getId()], [], true));
        $form->bind($ficheprofil);
        $form->init();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheProfilService()->create($ficheprofil);
                return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], ['fragment' => 'profil'], true);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/fiche-profil/modifier');
        $vm->setVariables([
            'type' => 'ajout',
            'structure' => $structure,
            'fichespostes' => $fichespostes,
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierAction()
    {
        $ficheprofil = $this->getFicheProfilService()->getRequestedFicheProfil($this);
        $structure = $ficheprofil->getStructure();
        $fichespostes = $this->getFichePosteService()->getFichesPostesByStructures([$structure], true);

        $form = $this->getFicheProfilForm();
        $form->setStructure($structure);
        $form->init();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-profil/modifier', ['fiche-profil' => $ficheprofil->getId()], [], true));
        $form->bind($ficheprofil);


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getFicheProfilService()->update($ficheprofil);
                return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], ['fragment' => 'profil'], true);
            }
        }

        $vm =  new ViewModel([
            'type' => 'modification',
            'structure' => $structure,
            'fichespostes' => $fichespostes,
            'form' => $form,
        ]);
        return $vm;
    }

    public function exporterAction()
    {
        $ficheprofil = $this->getFicheProfilService()->getRequestedFicheProfil($this);

        $this->getExporterService()->setVars([
            'type' => 'PROFIL_DE_RECRUTMENT',
            'ficheprofil' => $ficheprofil,
            'ficheposte' => $ficheprofil->getFichePoste(),
            'structure' => $ficheprofil->getStructure(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }
}