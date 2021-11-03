<?php

namespace Formation\Controller;

use Formation\Entity\Db\FormationInstanceJournee;
use Formation\Service\Emargement\EmargementPdfExporter;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceDocumentController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use RenduServiceAwareTrait;
    use TemplateServiceAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function exportEmargementAction()
    {
        $journee = $this->getFormationInstanceJourneeService()->getRequestedFormationInstanceJournee($this);

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'journee' => $journee,
        ]);

        //$filemane = "PrEECoG_Emargement_" . /** . $this->getDateTime()->format('YmdHis') . "_" . str_replace(" ", "_", $metier->getLibelle()) .**/ '.pdf';
        $filemane = "PrEECoG_Emargement.pdf";
        try {
            $exporter->getMpdf()->SetTitle($filemane);
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est surevenu lors du changement de titre par MPDF.", 0, $e);
        }
        $exporter->export($filemane);
        exit;
    }

    public function exportTousEmargementsAction()
    {
        $instance = $this->getFormationInstanceService()->getRequestedFormationInstance($this);
        $journees = $instance->getJournees();
        $journees = array_filter($journees, function (FormationInstanceJournee $a) {
            return $a->estNonHistorise();
        });

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([]);
        $filemane = "formation_" . $instance->getFormation()->getId() . "_du_" . str_replace("/", "-", $instance->getDebut()) . "_au_" . str_replace("/", "-", $instance->getFin()) . "_emargements.pdf";
        $exporter->exportAll($journees, $filemane);
        exit;
    }

    /**
     * @throws MpdfException
     */
    public function genererConvocationAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $vars = [
            'agent' => $inscrit->getAgent(),
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode('FORMATION_CONVOCATION', $vars);
        $exporter = new PdfExporter();
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('');
        $exporter->setFooterScript('');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }

    /**
     * @throws MpdfException
     */
    public function genererAttestationAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $vars = [
            'type' => '',
            'agent' => $inscrit->getAgent(),
            'inscrit' => $inscrit,
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode('FORMATION_ATTESTATION', $vars);
        $exporter = new PdfExporter();
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('');
        $exporter->setFooterScript('');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }

}