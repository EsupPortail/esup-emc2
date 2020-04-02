<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use DateTime;
use Exception;
use RuntimeException;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class FichePosteGraphViewHelper extends AbstractHelper {
    const MAX_LEN = 15;

    /**
     * @param FichePoste $ficheposte
     * @param string $mode
     * @param DateTime $date
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($ficheposte, $mode = 'radar', $date = null, $options = ['debug' => true])
    {
        $options['mode'] = $mode;
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        try {
            if ($date === null) $date = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Problème de récupération de la date", 0, $e);
        }

        $data = [];

        $allApplications = $ficheposte->getApplications($date);
        $conApplications = $ficheposte->getApplicationsConservees($date);
        $data['Applications'] = empty($allApplications)?0:(count($conApplications) / count($allApplications)) * 100;
        $allCompetences =  $ficheposte->getCompetences($date);
        $conCompetences = $ficheposte->getCompetencesConservees($date);
        $data['Compétences'] =  empty($allCompetences)?0:(count($conCompetences) / count($allCompetences)) * 100;
        $allFormations =  $ficheposte->getFormations($date);
        $conFormations =  $ficheposte->getFormationsConservees($date);
        $data['Formations'] = empty($allFormations)?0:(count($conFormations) / count($allFormations)) * 100;
        $allActivites =  $ficheposte->getActivites($date);
        $conActivites =  $ficheposte->getActivitesConservees($date);
        $data['Activités'] = empty($allActivites)?0:(count($conActivites) / count($allActivites)) * 100;

        foreach ($conActivites as $activite) {
            $libelle = $activite->getActivite()->getLibelle();
            if (strlen($libelle) > self::MAX_LEN) $libelle = substr($libelle, 0, self::MAX_LEN) . " ...";

            $allDescriptions = $ficheposte->getDescriptions($activite,$date);
            $conDescriptions = $ficheposte->getDescriptionsConservees($activite, $date);
            $data[$libelle] = empty($allDescriptions)?0:(count($conDescriptions) / count($allDescriptions)) * 100;
        }

        return $view->partial('ficheposte-graphique',
            [
                'ficheposte' => $ficheposte, 'mode' => $mode, 'data' => $data,
                'options' => $options
            ]);
    }
}
