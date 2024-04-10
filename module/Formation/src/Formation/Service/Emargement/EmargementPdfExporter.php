<?php

namespace Formation\Service\Emargement;

use Formation\Entity\Db\Seance;
use Mpdf\MpdfException;
use RuntimeException;
use UnicaenPdf\Exporter\PdfExporter as PdfExporter;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class EmargementPdfExporter extends PdfExporter
{
    private array $vars;

    public function setVars(array $vars) : EmargementPdfExporter
    {
        $this->vars = $vars;
        $this->vars['exporter'] = $this;

        return $this;
    }

    public function __construct(PhpRenderer $renderer = null, $format = 'A4', $orientationPaysage = false, $defaultFontSize = 10)
    {
        parent::__construct($renderer, $format, $orientationPaysage, $defaultFontSize);
        $resolver = $renderer->resolver();
        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__]]));
    }

    public function export($filename = null, $destination = self::DESTINATION_BROWSER, $memoryLimit = null) : string
    {
        //$this->addBodyHtml('<style>' . file_get_contents('/css/app.css') . '</style>');
        $this->setHeaderScript('empty.phtml');
        $this->setFooterScript('empty.phtml');
        $this->addBodyScript('emargement.phtml', false, $this->vars);
        return PdfExporter::export($filename, $destination, $memoryLimit);
    }

    /**
     * @param Seance[] $journees
     * @param null $filename
     * @param string $destination
     * @param null $memoryLimit
     * @return string
     */
    public function exportAll(array $journees, $filename = null, string $destination = self::DESTINATION_BROWSER, $memoryLimit = null): string
    {
        $session = reset($journees)->getInstance();
        $formateurs = $session->getFormateurs();
        $first = true;
        $this->setHeaderScript('empty.phtml');
        $this->setFooterScript('empty.phtml');
        foreach ($journees as $journee) {
            $this->vars["journee"] = $journee;
            $this->vars["formateurs"] = $formateurs;
            $this->addBodyScript('emargement.phtml', !$first, $this->vars);
            $first = false;
        }

        try {
            return PdfExporter::export($filename, $destination, $memoryLimit);
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors de la création du PDF",0,$e);
        }
    }
}