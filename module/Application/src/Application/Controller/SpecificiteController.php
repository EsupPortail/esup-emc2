<?php

namespace Application\Controller;

use Application\Entity\Db\SpecificiteActivite;
use Application\Service\Activite\ActiviteServiceAwareTrait;
use Application\Service\SpecificiteActivite\SpecificiteActiviteServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SpecificiteController extends AbstractActionController {
    use ActiviteServiceAwareTrait;
    use SpecificiteActiviteServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    /** ACTIVITES LIEES A LA SPECIFICITE DE POSTE *********************************************************************/

    public function ajouterActiviteAction()
    {
        $specificite = $this->getSpecificitePosteService()->getRequestedSpecificitePoste($this);
        $alreadyIn = [];
        foreach ($specificite->getActivites() as $activite) {
            $alreadyIn[$activite->getActivite()->getId()] = $activite;
        }
        $activites = $this->getActiviteService()->getActivites();

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $activite = $this->getActiviteService()->getActivite((int) $data['activite']);
            if ($activite !== null) {
                $specificiteActivite = new SpecificiteActivite();
                $specificiteActivite->setActivite($activite);
                $specificiteActivite->setSpecificite($specificite);
                $this->getSpecificiteActiviteService()->create($specificiteActivite);
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une activité spécifique au poste",
            'specificite' => $specificite,
            'activites' => $activites,
            'alreadyIn' => $alreadyIn,
        ]);
        return $vm;

    }

    public function retirerActiviteAction()
    {
        $specificiteActivite = $this->getSpecificiteActiviteService()->getRequestSpecificiteActivite($this);
        $retour = $this->params()->fromQuery('retour');

        $this->getSpecificiteActiviteService()->delete($specificiteActivite);

        if ($retour) return $this->redirect()->toRoute($retour);
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $specificiteActivite->getSpecificite()->getFiche()->getId()], [] ,true);
    }

    public function gererActiviteAction()
    {
        $specificiteActivite = $this->getSpecificiteActiviteService()->getRequestSpecificiteActivite($this);

        $descriptions = $specificiteActivite->getActivite()->getDescriptions();
        $descriptions = array_filter($descriptions, function ($a) { return $a->estNonHistorise();});

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $toRemove = [];
            foreach ($descriptions as $description) {
                if (!isset($data["".$description->getId()]) OR $data["".$description->getId()] !== "on") $toRemove[] = $description->getId();
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