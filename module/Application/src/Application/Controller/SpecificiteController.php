<?php

namespace Application\Controller;

use Application\Entity\Db\SpecificitePoste;
use FichePoste\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FichePoste\Service\MissionAdditionnelle\MissionAdditionnelleServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SpecificiteController extends AbstractActionController
{
    use FichePosteServiceAwareTrait;
    use MissionAdditionnelleServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    /** TODO Déclarer la gestion du formulaire de specificité ici et non plus dans le controller FichePosteController */

    /** ACTIVITES LIEES A LA SPECIFICITE DE POSTE *********************************************************************/

    public function ajouterActiviteAction() : ViewModel
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this);
        $specificite = $this->getSpecificitePosteService()->getRequestedSpecificitePoste($this);
        if ($specificite === null) $specificite = $fichePoste->getSpecificite();
//
        if ($specificite === null) {
            $specificite = new SpecificitePoste();
            $specificite->setFiche($fichePoste);
            $this->getSpecificitePosteService()->create($specificite);
            $fichePoste->setSpecificite($specificite);
            $this->getFichePosteService()->update($fichePoste);
        }

        $alreadyIn = [];
        foreach ($specificite->getActivites() as $activite) {
            $alreadyIn[$activite->getActivite()->getId()] = $activite;
        }
        $activites = $this->getMissionPrincipaleService()->getMissionsPrincipales();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $mission = $this->getMissionPrincipaleService()->getMissionPrincipale((int)$data['activite']['id']);
            if ($mission !== null) {
                $this->getMissionAdditionnelleService()->ajouterMissionAdditionnelle($fichePoste, $mission);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une mission additionnelle au poste",
            'fichePoste' => $fichePoste,
            'specificite' => $specificite,
            'activites' => $activites,
            'alreadyIn' => $alreadyIn,
            'url' => $this->url()->fromRoute('specificite/ajouter-activite', ['fiche-poste' => $fichePoste->getId(), 'specificite-poste' => $specificite->getId()], [], true),
        ]);
        return $vm;
    }

    public function retirerActiviteAction() : ViewModel
    {
        $missionadditionnelle = $this->getMissionAdditionnelleService()->getRequestedMissionAdditionnelle($this, 'specificite-activite');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getMissionAdditionnelleService()->delete($missionadditionnelle);
            exit();
        }

        $vm = new ViewModel();
        if ($missionadditionnelle !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission additionnelle <strong>" . $missionadditionnelle->getMission()->getLibelle() . '</strong>',
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('specificite/retirer-activite', ["specificite-activite" => $missionadditionnelle->getId()], [], true),
            ]);
        }
        return $vm;
    }

    public function gererActiviteAction() : ViewModel
    {
        $missionadditionnelle = $this->getMissionAdditionnelleService()->getRequestedMissionAdditionnelle($this, 'specificite-activite');

        $descriptions = $missionadditionnelle->getMission()->getActivites();
        $descriptions = array_filter($descriptions, function ($a) {
            return $a->estNonHistorise();
        });

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $toRemove = [];
            foreach ($descriptions as $description) {
                if (!isset($data["" . $description->getId()]) or $data["" . $description->getId()] !== "on") $toRemove[] = $description->getId();
            }
            $retrait = implode(";", $toRemove);
            $missionadditionnelle->setRetraits($retrait);
            $this->getMissionAdditionnelleService()->update($missionadditionnelle);
        }

        return new ViewModel([
            'title' => 'Sélectionner les descriptions à conserver',
            'missionAdditionnelle' => $missionadditionnelle,
            'descriptions' => $descriptions,
        ]);
    }
}