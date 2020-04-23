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
     * @param array $dictionnaires
     * @param string $mode
     * @param DateTime $date
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($ficheposte, $dictionnaires = [], $mode = 'radar', $date = null, $options = ['debug' => true])
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

        $data['Applications'] = 0;
        $data['Compétences'] = 0;
        $data['Formations'] = 0;
        if (isset($dictionnaires["applications"]) AND $dictionnaires["applications"] !== null) {
            $nbApplicationsConservees = 0; $nbApplications = count($dictionnaires["applications"]);
            foreach ($dictionnaires["applications"] as $item) if ($item["conserve"] === true) $nbApplicationsConservees++;
            $data['Applications'] = $nbApplicationsConservees / $nbApplications * 100;
        }
        if (isset($dictionnaires["competences"]) AND $dictionnaires["competences"] !== null) {
            $nbCompetencesConservees = 0; $nbCompetences = count($dictionnaires["competences"]);
            foreach ($dictionnaires["competences"] as $item) if ($item["conserve"] === true) $nbCompetencesConservees++;
            $data['Compétences'] = $nbCompetencesConservees / $nbCompetences * 100;
        }
        if (isset($dictionnaires["formations"]) AND $dictionnaires["formations"] !== null) {
            $nbFormationsConservees = 0; $nbFormations = count($dictionnaires["formations"]);
            foreach ($dictionnaires["formations"] as $item) if ($item["conserve"] === true) $nbFormationsConservees++;
            $data['Formations'] = $nbFormationsConservees / $nbFormations * 100;
        }

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
