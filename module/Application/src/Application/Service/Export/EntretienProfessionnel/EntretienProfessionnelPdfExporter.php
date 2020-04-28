<?php

namespace Application\Service\Export\EntretienProfessionnel;

use UnicaenApp\Exporter\Pdf as PdfExporter;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class EntretienProfessionnelPdfExporter extends PdfExporter
{
    private $vars;

    public function setVars(array $vars)
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

    public function export($filename = null, $destination = self::DESTINATION_BROWSER, $memoryLimit = null)
    {
        //$this->addBodyHtml('<style>' . file_get_contents('/css/app.css') . '</style>');
        $this->setHeaderScript('empty.phtml');
        $this->setFooterScript('empty.phtml');
        $this->addBodyScript('entretien-professionnel.phtml', false, $this->vars);
        return PdfExporter::export($filename, $destination, $memoryLimit);
    }

    /**
     * @param FicheMetier[] $fiches
     * @param null $filename
     * @param string $destination
     * @param null $memoryLimit
     * @return string
     */
    public function exportAll($fiches, $filename = null, $destination = self::DESTINATION_BROWSER, $memoryLimit = null)
    {
        $first = true;
        $this->setHeaderScript('empty.phtml');
        $this->setFooterScript('empty.phtml');
        foreach ($fiches as $fiche) {
            $this->vars["fiche"] = $fiche;
            $this->addBodyScript('fiche-metier.phtml', !$first, $this->vars);
            $first = false;
        }
        return PdfExporter::export($filename, $destination, $memoryLimit);
    }

}