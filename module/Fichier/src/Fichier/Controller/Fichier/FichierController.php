<?php

namespace Fichier\Controller\Fichier;

use Fichier\Entity\Db\Fichier;
use Fichier\Form\Upload\UploadFormAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Fichier\Service\Nature\NatureServiceAwareTrait;
use Zend\Http\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class FichierController extends AbstractActionController {
    use FichierServiceAwareTrait;
    use NatureServiceAwareTrait;
    use UploadFormAwareTrait;

    public function uploadAction()
    {
        $fichier = new Fichier();
        $form = $this->getUploadForm();
        $form->setAttribute('action', $this->url()->fromRoute('upload-fichier',[] , [], true));
        $form->bind($fichier);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = current($request->getFiles());

            if ($file['name'] != '') {

                $nature = $this->getNatureService()->getNature($data['nature']);
                $fichier = $this->getFichierService()->createFichierFromUpload($file, $nature);
            }
        }

        $vm =  new ViewModel();
        //$vm->setTemplate('fichier\default\default-form');
        $vm->setVariables([
            'title' => 'Téléverserment d\'un fichier',
            'form' => $form,
        ]);
        return $vm;
    }

    public function downloadAction()
    {
        $fichier = $this->getFichierService()->getRequestedFichier($this, 'fichier');

        $contentType = $fichier->getTypeMime() ?: 'application/octet-stream';
        $contenuFichier = $this->getFichierService()->fetchContenuFichier($fichier);

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

    public function deleteAction() {
        $fichier = $this->getFichierService()->getRequestedFichier($this, 'fichier');

        $this->getFichierService()->removeFichier($fichier);
    }
}