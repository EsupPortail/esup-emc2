<?php

namespace Carriere\Controller;

use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use Laminas\Http\Response;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class CorpsController extends AbstractActionController {
    use AgentGradeServiceAwareTrait;
    use CategorieServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use NiveauEnveloppeFormAwareTrait;

    public function indexAction() : ViewModel
    {
        $avecAgent = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::CORPS_AVEC_AGENT);
        $bool = ($avecAgent) && ($avecAgent->getValeur() === "true");
        $corps = $this->getCorpsService()->getCorps('libelleLong', 'ASC', $bool);

        return new ViewModel([
            "corps" => $corps,
        ]);
    }

    public function afficherAgentsAction() : ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE,CarriereParametres::ACTIF_ONLY);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $corps = $this->getCorpsService()->getRequestedCorps($this);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByCorps($corps, $bool);
        $agents = [];
        foreach ($agentGrades as $agentGrade) {
            $agents[$agentGrade->getAgent()->getId()] = $agentGrade->getAgent();
        }

        return new ViewModel([
            'title' => 'Agents ayant le corps ['. $corps->getLibelleCourt().']',
            'corps' => $corps,
            'agentGrades' => $agentGrades,
            'agents' => $agents,
        ]);
    }

    public function modifierNiveauxAction() : ViewModel
    {
        $corps = $this->getCorpsService()->getRequestedCorps($this);

        $niveaux = $corps->getNiveaux();
        if ($niveaux === null) {
            $niveaux = new NiveauEnveloppe();
        }

        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('corps/modifier-niveaux', ['corps' => $corps->getId()], [], true));
        $form->bind($niveaux);

        $request = $this->getRequest();
        if($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($niveaux->getHistoCreation()) {
                    $this->getNiveauEnveloppeService()->update($niveaux);
                } else {
                    $this->getNiveauEnveloppeService()->create($niveaux);
                    $corps->setNiveaux($niveaux);
                    $this->getCorpsService()->update($corps);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier les niveaux du corps [".$corps->getLibelleLong()."]",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function toggleSuperieurAutoriteAction(): Response
    {
        $corps = $this->getCorpsService()->getRequestedCorps($this);
        $corps->setSuperieurAsAutorite(!$corps->isSuperieurAsAutorite());
        $this->getCorpsService()->update($corps);

        return $this->redirect()->toRoute('corps', [], [],true);
    }
}