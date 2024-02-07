<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\ActionCoutPrevisionnel;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\PlanDeFormation;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class CoutsPrevisionnelsViewHelper extends AbstractHelper
{
    /**
     * @param ActionCoutPrevisionnel[] $couts
     */
    public function __invoke(array $couts, ?Formation $action = null, ?PlanDeFormation $plan = null, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('couts-previsionnels', ['couts' => $couts, 'action' => $action, 'plan' => $plan]);
    }
}