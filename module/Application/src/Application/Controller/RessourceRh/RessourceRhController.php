<?php

namespace Application\Controller\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Application\Entity\Db\Corps;
use Application\Entity\Db\Correspondance;
use Application\Entity\Db\Domaine;
use Application\Entity\Db\Metier;
use Application\Entity\Db\MetierFamille;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\MetierFamilleForm;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\AgentStatusForm;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class RessourceRhController extends AbstractActionController {
    use RessourceRhServiceAwareTrait;

    public function indexAction()
    {
        $status = $this->getRessourceRhService()->getAgentStatusListe('libelle');
        $correspondances = $this->getRessourceRhService()->getCorrespondances('libelle');
        $metiers = $this->getRessourceRhService()->getMetiers('libelle');
        $corps = $this->getRessourceRhService()->getCorpsListe('libelle');
        $familles = $this->getRessourceRhService()->getMetiersFamilles('libelle');
        $domaines = $this->getRessourceRhService()->getDomaines('libelle');

        return new ViewModel([
            'status' => $status,
            'correspondances' => $correspondances,
            'metiers' => $metiers,
            'familles' => $familles,
            'corps' => $corps,
            'domaines' => $domaines,
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un nouveau status',
            'form' => $form,
        ]);
        return $vm;
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer un status',
            'form' => $form,
        ]);
        return $vm;
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle correspondance',
            'form' => $form,
        ]);
        return $vm;
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une correspondance',
            'form' => $form,
        ]);
        return $vm;
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un nouveau corps',
            'form' => $form,
        ]);
        return $vm;
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

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer un corps',
            'form' => $form,
        ]);
        return $vm;
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

    /** METIER ********************************************************************************************************/

    public function creerMetierAction()
    {
        $metier = new Metier();

        /** @var CorpsForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/metier/creer', [], [], true));
        $form->bind($metier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createMetier($metier);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un nouveau métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierMetierAction()
    {
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getRessourceRhService()->getMetier($metierId);

        /** @var MetierForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/metier/modifier', [], [], true));
        $form->bind($metier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateMetier($metier);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer un métier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerMetierAction()
    {
        $metierId = $this->params()->fromRoute('id');
        $metier = $this->getRessourceRhService()->getMetier($metierId);

        if ($metier !== null) {
            $this->getRessourceRhService()->deleteMetier($metier);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** FAMILLE METIER ************************************************************************************************/

    public function creerFamilleAction()
    {
        $famille = new MetierFamille();

        /** @var CorpsForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierFamilleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/famille/creer', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createMetierFamille($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter une nouvelle famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierFamilleAction()
    {
        $familleId = $this->params()->fromRoute('id');
        $famille = $this->getRessourceRhService()->getMetierFamille($familleId);

        /** @var MetierForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(MetierFamilleForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/famille/modifier', [], [], true));
        $form->bind($famille);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateMetierFamille($famille);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Éditer une famille de métiers',
            'form' => $form,
        ]);
        return $vm;
    }

    public function effacerFamilleAction()
    {
        $familleId = $this->params()->fromRoute('id');
        $famille = $this->getRessourceRhService()->getMetierFamille($familleId);

        if ($famille !== null) {
            $this->getRessourceRhService()->deleteMetierFamille($famille);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

    /** DOMAINE *******************************************************************************************************/

    public function ajouterDomaineAction()
    {
        /** @var Domaine $domaine */
        $domaine = new Domaine();

        /** @var DomaineForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(DomaineForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/domaine/ajouter', [], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->createDomaine($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Ajouter un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function modifierDomaineAction()
    {
        /** @var Domaine $domaine */
        $domaineId = $this->params()->fromRoute('domaine');
        $domaine = $this->getRessourceRhService()->getDomaine($domaineId);

        /** @var DomaineForm $form */
        $form = $this->getServiceLocator()->get('FormElementManager')->get(DomaineForm::class);
        $form->setAttribute('action', $this->url()->fromRoute('ressource-rh/domaine/modifier', ['domaine' => $domaine->getId()], [], true));
        $form->bind($domaine);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getRessourceRhService()->updateDomaine($domaine);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Modifier un domaine',
            'form' => $form,
        ]);
        return $vm;
    }

    public function supprimerDomaineAction()
    {
        /** @var Domaine $domaine */
        $domaineId = $this->params()->fromRoute('domaine');
        $domaine = $this->getRessourceRhService()->getDomaine($domaineId);

        if ($domaine !== null) {
            $this->getRessourceRhService()->deleteDomaine($domaine);
        }

        $this->redirect()->toRoute('ressource-rh', [], [], true);
    }

}