<?php

namespace Application\Controller;

use Application\Entity\Db\FicheProfil;
use Application\Form\FicheProfil\FicheProfilFormAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\FicheProfil\FicheProfilServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenDocument\Service\Exporter\ExporterServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FicheProfilController extends AbstractActionController {
    use ExporterServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use FicheProfilServiceAwareTrait;
    use StructureServiceAwareTrait;
    use FicheProfilFormAwareTrait;
    use ParametreServiceAwareTrait;

    public function ajouterAction()
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        $fichespostes = [];
        foreach ($structures as $structure) {
            foreach ($structure->getFichesPostesRecrutements() as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;
        }

        $adresse = $this->getParametreService()->getParametreByCode('PROFIL_DE_RECRUTEMENT', 'ADRESSE_DEFAUT')->getValeur();

        $ficheprofil = new FicheProfil();
        $ficheprofil->setAdresse($adresse);
        $ficheprofil->setVancanceEmploi(false);
        $ficheprofil->setStructure($structure);

        $form = $this->getFicheProfilForm();
        $form->setStructure($structure);
        $form->setAttribute('action', $this->url()->fromRoute('fiche-profil/ajouter', ['structure' => $structure->getId()], [], true));
        $form->init();
        $form->bind($ficheprofil);

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

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        $fichespostes = [];
        foreach ($structures as $structure) {
            foreach ($structure->getFichesPostesRecrutements() as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;
        }

        $adresse = $this->getParametreService()->getParametreByCode('PROFIL_DE_RECRUTEMENT', 'ADRESSE_DEFAUT')->getValeur();
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
        $ficheposte = $ficheprofil->getFichePoste();
        $ficheposte->addDictionnaire('competences', $this->getFichePosteService()->getCompetencesDictionnaires($ficheposte));

        $this->getExporterService()->setVars([
            'type' => 'PROFIL_DE_RECRUTMENT',
            'ficheprofil' => $ficheprofil,
            'ficheposte' => $ficheposte,
            'structure' => $ficheprofil->getStructure(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

    public function historiserAction()
    {
        $ficheprofil = $this->getFicheProfilService()->getRequestedFicheProfil($this);
        $this->getFicheProfilService()->historise($ficheprofil);
        return $this->redirect()->toRoute('structure/afficher', ['structure' => $ficheprofil->getStructure()->getId()], ['fragment' => 'profil'], true);
    }

    public function restaurerAction()
    {
        $ficheprofil = $this->getFicheProfilService()->getRequestedFicheProfil($this);
        $this->getFicheProfilService()->restore($ficheprofil);
        return $this->redirect()->toRoute('structure/afficher', ['structure' => $ficheprofil->getStructure()->getId()], ['fragment' => 'profil'], true);
    }

    public function supprimerAction()
    {
        $ficheprofil = $this->getFicheProfilService()->getRequestedFicheProfil($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getFicheProfilService()->delete($ficheprofil);
            exit();
        }

        $vm = new ViewModel();
        if ($ficheprofil !== null) {
            $vm->setTemplate('unicaen-utilisateur/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression du profil de recrutement",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('fiche-profil/supprimer', ["fiche-profil" => $ficheprofil->getId()], [], true),
            ]);
        }
        return $vm;
    }
}