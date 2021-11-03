<?php

namespace Formation\Service\Emargement;

use Formation\Entity\Db\FormationInstanceJournee;
use UnicaenPdf\Exporter\PdfExporter as PdfExporter;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class EmargementPdfExporter extends PdfExporter
{
    private $vars;

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
     * @param FormationInstanceJournee[] $journees
     * @param null $filename
     * @param string $destination
     * @param null $memoryLimit
     * @return string
     */
    public function exportAll(array $journees, $filename = null, $destination = self::DESTINATION_BROWSER, $memoryLimit = null)
    {
        $first = true;
        $this->setHeaderScript('empty.phtml');
        $this->setFooterScript('empty.phtml');
        foreach ($journees as $journee) {
            $this->vars["journee"] = $journee;
            $this->addBodyScript('emargement.phtml', !$first, $this->vars);
            $first = false;
        }
        return PdfExporter::export($filename, $destination, $memoryLimit);
    }
}