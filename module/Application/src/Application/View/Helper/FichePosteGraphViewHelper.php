<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use DateTime;
use Exception;
use RuntimeException;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

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

        /** Applications, Compétences et Formations *******************************************************************/
        $nbApplicationsConservees = 0;
        $nbApplications = count($dictionnaires["applications"]);
        foreach ($dictionnaires["applications"] as $item) if ($item["conserve"] === true) $nbApplicationsConservees++;
        $data['Applications'] = ($nbApplications !== 0) ? $nbApplicationsConservees / $nbApplications * 100 : 0;

        $nbCompetencesConservees = 0;
        $nbCompetences = count($dictionnaires["competences"]);
        foreach ($dictionnaires["competences"] as $item) if ($item["conserve"] === true) $nbCompetencesConservees++;
        $data['Compétences'] = ($nbCompetences !== 0) ? $nbCompetencesConservees / $nbCompetences * 100 : 0;

        $nbFormationsConservees = 0;
        $nbFormations = count($dictionnaires["formations"]);
        foreach ($dictionnaires["formations"] as $item) if ($item["conserve"] === true) $nbFormationsConservees++;
        $data['Formations'] = ($nbFormations !== 0) ? $nbFormationsConservees / $nbFormations * 100 : 0;

        /** Activites *************************************************************************************************/
        $nbActivitesConservees = 0;
        $nbActivites = count($dictionnaires["activites"]);
        foreach ($dictionnaires["activites"] as $item) if ($item["conserve"] === true) $nbActivitesConservees++;
        $data['Activités'] = ($nbActivites !== 0) ?$nbActivitesConservees / $nbActivites * 100 : 0;

        foreach ($dictionnaires["activites"] as $item) {
            if ($item["conserve"] === true) {
                $activite = $item["object"];
                $libelle = $activite->getActivite()->getLibelle();
                if (strlen($libelle) > self::MAX_LEN) $libelle = substr($libelle, 0, self::MAX_LEN) . " ...";

                $allDescriptions = $ficheposte->getDescriptions($activite);
                $conDescriptions = $ficheposte->getDescriptionsConservees($activite);
                $data[$libelle] = empty($allDescriptions) ? 0 : (count($conDescriptions) / count($allDescriptions)) * 100;
            }
        }

        return $view->partial('ficheposte-graphique',
            [
                'ficheposte' => $ficheposte, 'mode' => $mode, 'data' => $data,
                'options' => $options
            ]);
    }
}
