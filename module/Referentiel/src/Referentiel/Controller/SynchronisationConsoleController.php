<?php

namespace Referentiel\Controller;

use Laminas\Mvc\Console\Controller\AbstractConsoleController;
use Referentiel\Service\Synchronisation\SynchronisationServiceAwareTrait;

class SynchronisationConsoleController extends AbstractConsoleController {
    use SynchronisationServiceAwareTrait;

    private array $configs;

    public function setConfigs(array $configs) : void
    {
        $this->configs = $configs;
    }

    public function synchroniserAllAction() : string
    {
        $jobs = $this->configs;
//        usort($works, function ($a,$b) { return $a['order'] > $b['order'];});
        foreach ($jobs as $name => $job) {
            echo $this->getSynchronisationService()->synchronise($name);
        }
        return "done!\n";
    }

    public function synchroniserAction() : string
    {
        $request = $this->getRequest();
        $name = $request->getParam('name');
        echo $this->getSynchronisationService()->synchronise($name);
        return "done!\n";
    }
}