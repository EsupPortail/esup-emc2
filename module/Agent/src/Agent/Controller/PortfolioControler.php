<?php

namespace Agent\Controller;

use Agent\Provider\Parametre\AgentParametres;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Exception;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use UnicaenFichier\Entity\Db\Fichier;
use UnicaenFichier\Exception\FichierException;
use UnicaenFichier\Form\Upload\UploadFormAwareTrait;
use UnicaenFichier\Service\Fichier\FichierServiceAwareTrait;
use UnicaenFichier\Service\Nature\NatureServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class PortfolioControler extends AbstractActionController {

    use AgentServiceAwareTrait;
    use FichierServiceAwareTrait;
    use NatureServiceAwareTrait;
    use ParametreServiceAwareTrait;

    use UploadFormAwareTrait;

    public function portfolioAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $fichiers = $agent->getFichiers();

        $vm = new ViewModel([
            'agent' => $agent,
            'fichiers' => $fichiers,
            // onglet
            'parametres' => $this->getParametreService()->getParametresByCategorieCode(AgentParametres::TYPE),
        ]);
        $vm->setTemplate('agent/portfolio/portfolio');
        return $vm;
    }

    public function afficherAction(): ViewModel|Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        try {
            $fichier = $this->getFichierService()->getRequestedFichier($this);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération du fichier", 0, $e);
        }

        if (!$fichier) {
            return $this->notFoundAction();
        }
        $contentType = $fichier->getTypeMime() ?: 'application/octet-stream';
        $contenuFichier = $this->getFichierService()->getStorageFileContent($fichier);

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename=' . $fichier->getNomOriginal());
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . $fichier->getTaille());
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');

        echo $contenuFichier;
        exit;
    }

    public function ajouterAction(): ViewModel|Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);

        $fichier = new Fichier();
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/portfolio/ajouter', ['agent' => $agent?->getId()], [], true));
        $form->bind($fichier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles();
            $file = $files['fichier'];

            if ($file['name'] != '') {
                try {
                    $nature = $this->getNatureService()->getNature($data['nature']);
                    $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
                } catch (Exception $e) {
                    throw new RuntimeException("Un problème est survenu lors du téléversement", 0, $e);
                }
                $agent->addFichier($fichier);
                $this->getAgentService()->update($agent);
            }

            $retour = $this->params()->fromQuery('retour');
            if ($retour) return $this->redirect()->toUrl($retour);
            exit();
        }

        $vm = new ViewModel([
            'title' => "Ajout d'un document au portfolio",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        try {
            $fichier = $this->getFichierService()->getRequestedFichier($this);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération du fichier", 0, $e);
        }

        try {
            $this->getFichierService()->historise($fichier);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'historisation", 0, $e);
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/portfolio', ['agent' => $agent?->getId()], [], true);
    }

    public function restaurerAction(): Response
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        try {
            $fichier = $this->getFichierService()->getRequestedFichier($this);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération du fichier", 0, $e);
        }

        try {
            $this->getFichierService()->restore($fichier);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de la restauration du fichier", 0, $e);
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('agent/portfolio', ['agent' => $agent?->getId()], [], true);
    }

    public function supprimerAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        try {
            $fichier = $this->getFichierService()->getRequestedFichier($this);
        } catch (FichierException $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération du fichier", 0, $e);
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") try {
                $this->getFichierService()->delete($fichier);
            } catch (FichierException $e) {
                throw new RuntimeException("Un problème est survenu lors de la suppression du fichier", 0, $e);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($fichier !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'un document " . $fichier->getNomOriginal(),
                'text' => "La suppression est définitive, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/portfolio/supprimer', ["agent" => $agent?->getId(), $fichier->getId() ], [], true),
            ]);
        }
        return $vm;
    }



}