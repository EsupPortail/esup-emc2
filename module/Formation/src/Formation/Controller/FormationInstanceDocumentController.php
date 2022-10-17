<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use Formation\Entity\Db\Seance;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Template\PdfTemplates;
use Formation\Service\Emargement\EmargementPdfExporter;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;

class FormationInstanceDocumentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use MacroServiceAwareTrait;
    use RenduServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use TemplateServiceAwareTrait;

    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function exportEmargementAction()
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'journee' => $journee,
        ]);

        //$filemane = "PrEECoG_Emargement_" . /** . (new DateTime())->format('YmdHis') . "_" . str_replace(" ", "_", $metier->getLibelle()) .**/ '.pdf';
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
        $journees = array_filter($journees, function (Seance $a) {
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
    public function genererConvocationAction() : ?string
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $vars = [
            'agent' => $inscrit->getAgent(),
            'formation' => $inscrit->getInstance()->getFormation(),
            'session' => $inscrit->getInstance(),
            'MacroService' => $this->getMacroService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_CONVOCATION, $vars);
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc');
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }

    /**
     * @throws MpdfException
     */
    public function genererAttestationAction() : ?string
    {
        $inscrit = $this->getFormationInstanceInscritService()->getRequestedFormationInstanceInscrit($this);

        $vars = [
            'type' => '',
            'agent' => $inscrit->getAgent(),
            'inscription' => $inscrit,
            'formation' => $inscrit->getInstance()->getFormation(),
            'session' => $inscrit->getInstance(),
            'MacroService' => $this->getMacroService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_ATTESTATION, $vars);
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc');
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }

    /**
     * @throws MpdfException
     */
    public function genererHistoriqueAction() : ?string
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $inscriptions = $this->getFormationInstanceInscritService()->getFormationsBySuivies($agent);


        $texte  = "<ul>";
        foreach ($inscriptions as $inscription) {
            $dureeSuivie = $inscription->getDureePresence();
            $session = $inscription->getInstance();
            $sessionEtat = $session->getEtat()->getCode();
            if ($dureeSuivie != '0 heures ' AND ($sessionEtat === SessionEtats::ETAT_CLOTURE_INSTANCE OR $sessionEtat === SessionEtats::ETAT_ATTENTE_RETOURS)) {
                $libelle = $session->getFormation()->getLibelle();
                $periode = $session->getPeriode();
                $texte .= "<li>";
                $texte .= $libelle . ($periode) . "<br/>";
                $texte .= $dureeSuivie . " suivies sur " . $session->getDuree() . " de formation";
                $texte .= "</li>";
            }
        }
        $texte .= "<ul>";

        $vars = [
            'agent' => $agent,
            'MacroService' => $this->getMacroService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_HISTORIQUE, $vars);
        $corps = str_replace("###A REMPLACER###", $texte, $rendu->getCorps());
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc');
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($corps);
        return $exporter->export($rendu->getSujet(), PdfExporter::DESTINATION_BROWSER, null);
    }

}