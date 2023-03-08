<?php

namespace Referentiel\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Referentiel\Service\Synchronisation\SynchronisationServiceAwareTrait;
use UnicaenDbImport\Controller\ConsoleController;

class SynchronisationConsoleController extends ConsoleController {
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
}