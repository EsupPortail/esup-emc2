<?php

namespace Formation\Controller;

use Application\Service\Export\Formation\Emargement\EmargementPdfExporter;
use Formation\Entity\Db\FormationInstanceJournee;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenDocument\Service\Exporter\ExporterServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceDocumentController extends AbstractActionController
{
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;
    use ExporterServiceAwareTrait;

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

        $this->getExporterService()->setVars([
            'type' => 'FORMATION_CONVOCATION',
            'agent' => $inscrit->getAgent(),
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

    /**
     * @throws MpdfException
     */
    public function genererAttestationAction()
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $this->getExporterService()->setVars([
            'type' => 'FORMATION_ATTESTATION',
            'agent' => $inscrit->getAgent(),
            'inscrit' => $inscrit,
            'formation' => $inscrit->getInstance()->getFormation(),
            'instance' => $inscrit->getInstance(),
        ]);
        $this->getExporterService()->export('export.pdf');
        exit;
    }

}