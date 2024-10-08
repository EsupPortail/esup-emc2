<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\Seance;
use Formation\Entity\Db\Session;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Template\PdfTemplates;
use Formation\Service\Emargement\EmargementPdfExporter;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Formation\Service\Url\UrlServiceAwareTrait;
use JetBrains\PhpStorm\NoReturn;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Renderer\PhpRenderer;
use Mpdf\MpdfException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;

class FormationInstanceDocumentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use SessionServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use MacroServiceAwareTrait;
    use RenduServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use TemplateServiceAwareTrait;
    use UrlServiceAwareTrait;

    private ?PhpRenderer $renderer = null;

    public function setRenderer(PhpRenderer $renderer): void
    {
        $this->renderer = $renderer;
    }

    private ?array $etablissementInfos = null;

    public function addValeur(string $clef, ?string $valeur): void
    {
        $this->etablissementInfos[$clef] = $valeur;
    }

    public function exportEmargementAction(): void
    {
        $journee = $this->getSeanceService()->getRequestedSeance($this);
        $session = $journee->getInstance();
        $formateurs = $session->getFormateurs();

        $exporter = new EmargementPdfExporter($this->renderer, 'A4');
        $exporter->setVars([
            'journee' => $journee,
            'formateurs' => $formateurs,
        ]);

        //$filemane = "PrEECoG_Emargement_" . /** . (new DateTime())->format('YmdHis') . "_" . str_replace(" ", "_", $metier->getLibelle()) .**/ '.pdf';
        $filemane = "PrEECoG_Emargement.pdf";
        try {
            $exporter->getMpdf()->SetTitle($filemane);
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est surevenu lors du changement de titre par MPDF.", 0, $e);
        }
        try {
            $exporter->export($filemane);
        } catch (MpdfException $e) {
            throw new RuntimeException("Une erreur est survenu lors de la génération du PDF", 0, $e);
        }
        exit;
    }

    #[NoReturn] public function exportTousEmargementsAction(): void
    {
        $instance = $this->getSessionService()->getRequestedSession($this);
        $journees = $instance->getSeances();
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
    public function genererConvocationAction(): ?string
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $vars = [
            'agent' => $inscription->getIndividu(),
            'formation' => $inscription->getSession()->getFormation(),
            'session' => $inscription->getSession(),
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_CONVOCATION, $vars);
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->getMpdf()->SetMargins(0, 0, 50);
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc', null, $this->etablissementInfos);
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet());
    }

    /**
     * @throws MpdfException
     */
    public function genererAttestationAction(): ?string
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $vars = [
            'type' => '',
            'agent' => $inscription->getIndividu(),
            'inscription' => $inscription,
            'formation' => $inscription->getSession()->getFormation(),
            'session' => $inscription->getSession(),
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_ATTESTATION, $vars);
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->getMpdf()->SetMargins(0, 0, 50);
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc', null, $this->etablissementInfos);
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet());
    }

    /**
     * @throws MpdfException
     */
    public function genererAbsenceAction(): ?string
    {
        $inscription = $this->getInscriptionService()->getRequestedInscription($this);

        $vars = [
            'type' => '',
            'agent' => $inscription->getIndividu(),
            'inscription' => $inscription,
            'formation' => $inscription->getSession()->getFormation(),
            'session' => $inscription->getSession(),
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_ABSENCE, $vars);
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->getMpdf()->SetMargins(0, 0, 50);
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc', null, $this->etablissementInfos);
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($rendu->getCorps());
        return $exporter->export($rendu->getSujet());
    }

    /**
     * @throws MpdfException
     */
    public function genererHistoriqueAction(): ?string
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $inscriptions = $this->getInscriptionService()->getInscriptionsByAgent($agent);


        $array = [];
        foreach ($inscriptions as $inscription) {
            $annee = Formation::getAnnee($inscription->getSession()->getDebut(true));
            $array[$annee][] = $inscription;
        }
        ksort($array);
        $array = array_reverse($array, true);

        $texte = "";
        foreach ($array as $annee => $inscriptions) {
            usort($inscriptions, function (Inscription $a, Inscription $b) {
                return $a->getSession()->getDebut(true) <=> $b->getSession()->getDebut(true);
            });
            $texte .= "<div>";
            $texte .= "<h3>Formations pour l'année " . $annee . "/" . ($annee + 1) . "</h3>";
            $texte .= "<ul>";
            foreach ($inscriptions as $inscription) {
                $dureeSuivie = $inscription->getDureePresence();
                /** @var Session $session */
                $session = $inscription->getSession();
                if ($dureeSuivie != '0 heures ' && ($session->isEtatActif(SessionEtats::ETAT_CLOTURE_INSTANCE) || $session->isEtatActif(SessionEtats::ETAT_ATTENTE_RETOURS))) {
                    $libelle = $session->getFormation()->getLibelle();
                    $periode = $session->getPeriode();
                    $texte .= "<li>";
                    $texte .= $libelle . " (" . $periode . ")<br/>";
                    $texte .= $dureeSuivie . " suivies sur " . $session->getDuree() . " de formation";
                    $texte .= "</li>";
                }
            }
            $texte .= "</ul>";
        }

        $vars = [
            'agent' => $agent,
            'MacroService' => $this->getMacroService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplates::FORMATION_HISTORIQUE, $vars);
        $corps = str_replace("###A REMPLACER###", $texte, $rendu->getCorps());
        $exporter = new PdfExporter();
        $exporter->setRenderer($this->renderer);
        $exporter->getMpdf()->SetTitle($rendu->getSujet());
        $exporter->setHeaderScript('/application/document/pdf/entete-logo-ccc', null, $this->etablissementInfos);
        $exporter->getMpdf()->SetMargins(0, 0, 50);
        $exporter->setFooterScript('/application/document/pdf/pied-vide');
        $exporter->addBodyHtml($corps);
        return $exporter->export($rendu->getSujet());
    }

}