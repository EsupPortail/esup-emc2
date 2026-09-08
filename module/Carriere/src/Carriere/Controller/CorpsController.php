<?php

namespace Carriere\Controller;

use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Provider\Role\RoleProvider as ApplicationRoleProvider;
use Application\Service\Util\UtilServiceAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Provider\Parametre\CarriereParametres;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienProfessionnelRoleProvider;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Provider\Role\RoleProvider as StructureRoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class CorpsController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UserServiceAwareTrait;
    use UtilServiceAwareTrait;

    use NiveauEnveloppeFormAwareTrait;



    public function indexAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);

        $avecAgent = $this->getParametreService()->getValeurForParametre(CarriereParametres::TYPE, CarriereParametres::CORPS_AVEC_AGENT) === true;
        $corps = $this->getCorpsService()->getCorps('libelleLong', 'ASC', false);

        $dictionnaire = $this->getAgentGradeService()->generateRecensementByCorps($agents);

        return new ViewModel([
            "corps" => $corps,
            "agents" => $agents,
            "dictionnaire" => $dictionnaire,

            "avecAgent" => $avecAgent,
        ]);
    }

    public function afficherAgentsAction(): ViewModel
    {
        $actifOnly = $this->getParametreService()->getParametreByCode(CarriereParametres::TYPE, CarriereParametres::ACTIF_ONLY);
        $bool = ($actifOnly) && ($actifOnly->getValeur() === "true");

        $corps = $this->getCorpsService()->getRequestedCorps($this);

        $user = $this->getUserService()->getConnectedUser();
        $agents = $this->getUtilService()->getAgentsSousReponsabilite($user);
        $dictionnaire = $this->getAgentGradeService()->generateDictionnaireWithCorps($corps, $agents);

        return new ViewModel([
            'title' => 'Agents ayant le corps [' . $corps->getLibelleCourt() . ']',
            'corps' => $corps,
            'agentGrades' => $dictionnaire,
            'agents' => $agents,
        ]);
    }

    public function modifierNiveauxAction(): ViewModel
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
        if ($request->isPost()) {
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
            'title' => "Modifier les niveaux du corps [" . $corps->getLibelleLong() . "]",
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

        return $this->redirect()->toRoute('corps', [], [], true);
    }

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $corps = $this->getCorpsService()->getCorpsByTerm($term);
            $result = $this->getCorpsService()->formatCorpsJSON($corps);
            return new JsonModel($result);
        }
        exit;
    }
}