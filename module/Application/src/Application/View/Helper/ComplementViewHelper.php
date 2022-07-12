<?php

namespace Application\View\Helper;

use Application\Entity\Db\Complement;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class ComplementViewHelper extends AbstractHelper
{
    use EntityManagerAwareTrait;

    /**
     * @param Complement $complement
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Complement $complement, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $string = "Aucune information ...";
        if ($complement->getComplementType() AND $complement->getComplementId()) {
            $object = $this->getEntityManager()->getRepository($complement->getComplementType())->find($complement->getComplementId());
            $string = "<span class='emc2' title='Connue de EMC2'>".$object->toString()."</span>";
        }
        if ($complement->getComplementText()) $string = "<span class='texte' title='Saisie libre'>" . $complement->getComplementText() . "</span>";

        return $view->partial('complement', ['string' => $string, 'options' => $options]);
    }
}