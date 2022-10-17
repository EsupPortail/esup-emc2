<?php

namespace Application\Controller;

use Application\Entity\Db\SpecificiteActivite;
use Application\Entity\Db\SpecificitePoste;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\SpecificiteActivite\SpecificiteActiviteServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class SpecificiteController extends AbstractActionController
{
    use ActiviteServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use SpecificiteActiviteServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    /** ACTIVITES LIEES A LA SPECIFICITE DE POSTE *********************************************************************/

    public function ajouterActiviteAction() : ViewModel
    {
        $fichePoste = $this->getFichePosteService()->getRequestedFichePoste($this);
        $specificite = $this->getSpecificitePosteService()->getRequestedSpecificitePoste($this);
        if ($specificite === null) $specificite = $fichePoste->getSpecificite();

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
        $activites = $this->getActiviteService()->getActivites();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $activite = $this->getActiviteService()->getActivite((int)$data['activite']['id']);
            if ($activite !== null) {
                $specificiteActivite = new SpecificiteActivite();
                $specificiteActivite->setActivite($activite);
                $specificiteActivite->setSpecificite($specificite);
                $this->getSpecificiteActiviteService()->create($specificiteActivite);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une activité spécifique au poste",
            'fichePoste' => $fichePoste,
            'specificite' => $specificite,
            'activites' => $activites,
            'alreadyIn' => $alreadyIn,
            'url' => $this->url()->fromRoute('specificite/ajouter-activite', ['fiche-poste' => $fichePoste->getId(), 'specificite-poste' => $specificite->getId()], [], true),
        ]);
        $vm->setTemplate('application/fiche-metier/ajouter-activite-existante');
        return $vm;

    }

    public function retirerActiviteAction() : Response
    {
        $specificiteActivite = $this->getSpecificiteActiviteService()->getRequestSpecificiteActivite($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getSpecificiteActiviteService()->delete($specificiteActivite);

        if ($retour) return $this->redirect()->toRoute($retour);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $specificiteActivite->getSpecificite()->getFiche()->getId()], [], true);
    }

    public function gererActiviteAction() : ViewModel
    {
        $specificiteActivite = $this->getSpecificiteActiviteService()->getRequestSpecificiteActivite($this);

        $descriptions = $specificiteActivite->getActivite()->getDescriptions();
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
            $specificiteActivite->setRetrait($retrait);
            $this->getSpecificiteActiviteService()->update($specificiteActivite);
        }

        return new ViewModel([
            'title' => 'Sélectionner les descriptions à conserver',
            'specificiteActivite' => $specificiteActivite,
            'descriptions' => $descriptions,
        ]);
    }
}