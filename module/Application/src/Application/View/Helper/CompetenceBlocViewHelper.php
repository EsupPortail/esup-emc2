<?php

namespace Application\View\Helper;

use Application\Entity\Db\CompetenceType;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class CompetenceBlocViewHelper extends AbstractHelper
{

    /** @var CompetenceType[] */
    private $competencesTypes;

    /**
     * @param array $competences
     * @param HasCompetenceCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $competences, ?HasCompetenceCollectionInterface $objet = null, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('competence-bloc', ['competences' => $competences, 'objet' => $objet, 'types' => $this->getCompetencesTypes(), 'options' => $options]);
    }

    /**
     * @return CompetenceType[]|null
     */
    public function getCompetencesTypes(): ?array
    {
        return $this->competencesTypes;
    }

    /**
     * @param CompetenceType[] $competencesTypes
     * @return CompetenceBlocViewHelper
     */
    public function setCompetencesTypes(?array $competencesTypes): CompetenceBlocViewHelper
    {
        $this->competencesTypes = $competencesTypes;
        return $this;
    }

    public static function isActionActivee(array $options, string $action) : bool
    {
        return (!isset($options['actions']) OR !isset($options['actions'][$action]) OR $options['actions'][$action] !== false);
    }


}