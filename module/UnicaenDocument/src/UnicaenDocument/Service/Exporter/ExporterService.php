<?php

namespace UnicaenDocument\Service\Exporter;

use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Exporter\Pdf as PdfExporter;
use UnicaenDocument\Service\Contenu\ContenuServiceAwareTrait;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class ExporterService extends PdfExporter {
    use ContenuServiceAwareTrait;

    private $vars;
    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
        $this->vars['exporter'] = $this;

        return $this;
    }

    public function __construct(PhpRenderer $renderer = null, $format = 'A4', $orientationPaysage = false, $defaultFontSize = 10)
    {
        if ($renderer === null) $renderer = $this->renderer;
        parent::__construct($renderer, $format, $orientationPaysage, $defaultFontSize);
        $resolver = $renderer->resolver();
        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__]]));
//        $resolver->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));
    }

    /**
     * @param null $filename
     * @param string $destination
     * @param null $memoryLimit
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function export($filename = null, $destination = self::DESTINATION_BROWSER, $memoryLimit = null)
    {
        if (!isset($this->vars['type'])) {
            throw new RuntimeException("Aucun type de document de spécifié dans les variables de l'exportation");
        }
        $contenu = $this->getContenuService()->getContenuByCode($this->vars['type']);
        if ($contenu === null) {
            throw new RuntimeException("Aucun contenu de récupéré pour le type [".$this->vars['type']."]");
        }


        $complement = $this->getContenuService()->generateComplement($contenu,$this->vars);
        $content    = $this->getContenuService()->generateContenu($contenu,$this->vars);
        $titre      = $this->getContenuService()->generateTitre($contenu,$this->vars);

        $this->vars['script'] = $content;
        $this->addBodyHtml($content, false, $this->vars);

        $this->getMpdf()->SetTopMargin(25);
        $this->getMpdf()->SetTitle($titre);
        return PdfExporter::export($complement, $destination, $memoryLimit);
    }
}