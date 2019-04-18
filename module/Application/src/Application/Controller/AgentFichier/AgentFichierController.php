<?php

namespace Application\Controller\AgentFichier;

use Application\Service\Agent\AgentServiceAwareTrait;
use Fichier\Entity\Db\Fichier;
use Fichier\Entity\Db\Nature;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Zend\Form\Element\Select;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AgentFichierController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use FichierServiceAwareTrait;
    use NatureServiceAwareTrait;

    use UploadFormAwareTrait;

    public function indexAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this,'agent');

        /** @var Fichier $cv */
        $cv = $agent->fetchFile(Nature::CV);

        /** @var Fichier $motiv */
        $motiv = $agent->fetchFile(Nature::MOTIV);

        /** @var Fichier[] $formations */
        $formations = $agent->fetchFiles(Nature::FORMATION);

        return new ViewModel([
            'agent' => $agent,
            'cv' => $cv,
            'motiv' => $motiv,
            'formations' => $formations,
        ]);
    }

    public function uploadAction()
    {
        $agent = $this->getAgentService()->getRequestedAgent($this,'agent');
        $nature = $this->getNatureService()->getNatureByCode($this->params()->fromRoute('nature'));

        $fichier = new Fichier();
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/upload-fichier',['agent' => $agent->getId(), 'nature' => $nature->getCode()] , [], true));
        $form->bind($fichier);

        if ($nature) {
            /** @var Select $select */
            $select = $form->get('nature');
            $select->setValueOptions([ $nature->getId() => $nature->getLibelle()]);
        }

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = current($request->getFiles());

            if ($file['name'] != '') {

                $nature = $this->getNatureService()->getNature($data['nature']);
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);

                $this->redirect()->toRoute('agent/fichiers', ['agent' => $agent->getId()], [], true);
            }
        }

        $vm =  new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }
}