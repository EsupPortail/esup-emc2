<?php

namespace Application\Controller\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Service\Metier\MetierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    use MetierServiceAwareTrait;
    use RessourceRhServiceAwareTrait;

    public function indexAction()
    {
        $status = $this->getRessourceRhService()->getAgentStatusListe('libelle');
        $correspondances = $this->getRessourceRhService()->getCorrespondances('libelle');
        $metiers = $this->getMetierService()->getMetiers('libelle');
        $corps = $this->getRessourceRhService()->getCorpsListe('libelle');

        return new ViewModel([
            'status' => $status,
            'correspondances' => $correspondances,
            'metiers' => $metiers,
            'corps' => $corps,
        ]);
    }

    /** AGENT STATUS **************************************************************************************************/

    public function creerAgentStatusAction()
    {
        $status = new AgentStatus();

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentStatusForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/agent-status/creer', [], [], true));
        $form->bind($status);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createAgentStatus($status);
            }
        }

        return new ViewModel([
            'title' => 'Ajouter un nouveau status',
            'form' => $form,
        ]);
    }

    public function modifierAgentStatusAction()
    {
        $statusId = $this->params()->fromRoute('id');
        $status = $this->getRessourceRhService()->getAgentStatus($statusId);

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(AgentStatusForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/agent-status/modifier', [], [], true));
        $form->bind($status);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateAgentStatus($status);
            }
        }

        return new ViewModel([
            'title' => 'Modifier un status',
            'form' => $form,
        ]);
    }

    public function effacerAgentStatusAction()
    {
        $statusId = $this->params()->fromRoute('id');
        $status = $this->getRessourceRhService()->getAgentStatus($statusId);

        if ($status !== null) {
            $this->getRessourceRhService()->deleteAgentStatus($status);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** CORRESPONDANCE ************************************************************************************************/

    public function creerCorrespondanceAction()
    {
        $correspondance = new Correspondance();

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(CorrespondanceForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/correspondance/creer', [], [], true));
        $form->bind($correspondance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createCorrespondance($correspondance);
            }
        }

        return new ViewModel([
            'title' => 'Ajouter une nouvelle correspondance',
            'form' => $form,
        ]);
    }

    public function modifierCorrespondanceAction()
    {
        $correspondanceId = $this->params()->fromRoute('id');
        $correspondance = $this->getRessourceRhService()->getCorrespondance($correspondanceId);

        /** @var AgentStatusForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(CorrespondanceForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/correspondance/modifier', [], [], true));
        $form->bind($correspondance);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateCorrespondance($correspondance);
            }
        }

        return new ViewModel([
            'title' => 'Modifier une correspondance',
            'form' => $form,
        ]);
    }

    public function effacerCorrespondanceAction()
    {
        $correspondanceId = $this->params()->fromRoute('id');
        $correspondance = $this->getRessourceRhService()->getCorrespondance($correspondanceId);

        if ($correspondance !== null) {
            $this->getRessourceRhService()->deleteCorrespondance($correspondance);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** CORPS *********************************************************************************************************/

    public function creerCorpsAction()
    {
        $corps = new Corps();

        /** @var CorpsForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(CorpsForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/corps/creer', [], [], true));
        $form->bind($corps);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createCorps($corps);
            }
        }

        return new ViewModel([
            'title' => 'Ajouter un nouveau corps',
            'form' => $form,
        ]);
    }

    public function modifierCorpsAction()
    {
        $corpsId = $this->params()->fromRoute('id');
        $corps = $this->getRessourceRhService()->getCorps($corpsId);

        /** @var CorpsForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(CorpsForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/corps/modifier', [], [], true));
        $form->bind($corps);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateCorps($corps);
            }
        }

        return new ViewModel([
            'title' => 'Modifier un corps',
            'form' => $form,
        ]);
    }

    public function effacerCorpsAction()
    {
        $corpsId = $this->params()->fromRoute('id');
        $corps = $this->getRessourceRhService()->getCorps($corpsId);

        if ($corps !== null) {
            $this->getRessourceRhService()->deleteCorps($corps);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

}