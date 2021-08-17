<?php

namespace UnicaenNote\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenNote\Entity\Db\PorteNote;
use UnicaenNote\Service\PorteNote\PorteNoteServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class PorteNoteViewHelper extends AbstractHelper
{
    use PorteNoteServiceAwareTrait;

    public $canVoir;
    public $canModifier;

    /**
     * @param PorteNote|string|null $portenote
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke( $portenote, $mode = 'affichage', $options = [])
    {
        if (is_string($portenote)) {
            $accroche = $portenote;
            $portenote = $this->getPorteNoteService()->getPorteNoteByAccroche($accroche);
            if (!$portenote) {
                $portenote = new PorteNote();
                $portenote->setAccroche($accroche);
                $this->getPorteNoteService()->create($portenote);
            }
        }

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $options['canModifier'] = $this->canModifier;
        return $view->partial('portenote', ['portenote' => $portenote, 'mode' => $mode, 'options' => $options]);
    }
}