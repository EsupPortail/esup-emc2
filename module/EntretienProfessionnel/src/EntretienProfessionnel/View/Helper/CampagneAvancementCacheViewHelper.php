<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\CampagneProgressionStructure;
use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureServiceAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use Structure\Entity\Db\Structure;

class CampagneAvancementCacheViewHelper extends AbstractHelper
{
    use CampagneProgressionStructureServiceAwareTrait;

    public ?PhpRenderer $renderer = null;
    public ?Campagne $campagne = null;
    public ?Structure $structure = null;
    public ?CampagneProgressionStructure $progression = null;
    public array $options = [];

    public function __invoke(Campagne $campagne, Structure $structure, ?CampagneProgressionStructure $progression = null, array $options = []): string|Partial
    {
        if ($progression === null) {
            $progression = $this->getCampagneProgressionStructureService()->getCampagneProgressionStructureByCampagneAndStructure($campagne, $structure);
        }

        $this->campagne = $campagne;
        $this->structure = $structure;
        $this->progression = $progression;
        $this->options = $options;

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement-cache', ['campagne' => $this->campagne, 'structure' => $this->structure, 'progression' => $progression, 'options' => $this->options]);
    }

    public function __toString() {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement-cache', ['campagne' => $this->campagne, 'structure' => $this->structure, 'progression' => $this->progression, 'options' => $this->options]);
    }

    public function render(string $mode = 'div') {
        $view = $this->renderer;
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-avancement-cache', ['campagne' => $this->campagne, 'structure' => $this->structure, 'progression' => $this->progression, 'options' => $this->options]);
    }

}
