<?php

namespace Element\View\Helper;

use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class CompetenceBlocViewHelper extends AbstractHelper
{

    /** @var CompetenceType[] */
    private array $competencesTypes = [];

    /**
     * @param array $competences
     * @param HasCompetenceCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $competences, ?HasCompetenceCollectionInterface $objet = null, array $options = [])
    {
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



    public static function isDisplayed(array $options, string $key)
    {
        return (!isset($options['display']) OR !isset($options['display'][$key]) OR $options['display'][$key] !== false);
    }

}