<?php

namespace FicheMetier\Service\FicheMetier;

use Application\Provider\Etat\FicheMetierEtats;
use Application\Provider\Template\PdfTemplate;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceDiscipline;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Form\SelectionApplication\SelectionApplicationHydratorAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceHydratorAwareTrait;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\HasApplicationCollection\HasApplicationCollectionServiceAwareTrait;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionServiceAwareTrait;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractController;
use Mpdf\MpdfException;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class FicheMetierService
{
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ConfigurationServiceAwareTrait;
    use ProvidesObjectManager;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MacroServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauFonctionServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;
    use ThematiqueTypeServiceAwareTrait;

    use HasApplicationCollectionServiceAwareTrait;
    use HasCompetenceCollectionServiceAwareTrait;

    use SelectionApplicationHydratorAwareTrait;
    use SelectionCompetenceHydratorAwareTrait;

    const REFERENS_SEP = "|";

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FicheMetier $fiche): void
    {
        $this->getObjectManager()->persist($fiche);
        $this->getObjectManager()->flush($fiche);
    }

    public function update(FicheMetier $fiche): void
    {
        $this->getObjectManager()->flush($fiche);
    }

    public function historise(FicheMetier $fiche): void
    {
        $fiche->historiser();
        $this->getObjectManager()->flush($fiche);
    }

    public function restore(FicheMetier $fiche): void
    {
        $fiche->dehistoriser();
        $this->getObjectManager()->flush($fiche);
    }

    public function delete(FicheMetier $fiche): void
    {
        $this->getObjectManager()->remove($fiche);
        $this->getObjectManager()->flush($fiche);
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('etat')->leftjoin('ficheMetier.etats', 'etat')
            ->addSelect('famille')->leftjoin('ficheMetier.familleProfessionnelle', 'famille')
            ->addSelect('etype')->leftjoin('etat.type', 'etype')
            ->addSelect('referentiel')->leftJoin('ficheMetier.referentiel', 'referentiel')
            ->addSelect('codeFonction')->leftJoin('ficheMetier.codeFonction', 'codeFonction');
        return $qb;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiers(string $order = 'id'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ficheMetier.', $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param array $filtre
     * @param string $champ
     * @param string $ordre
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithFiltre(array $filtre, string $champ = 'id', string $ordre = 'DESC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('ficheMetier.' . $champ, $ordre);

        if (isset($filtre['etat']) and $filtre['etat'] != '') {
            $qb = $qb->andWhere('etype.id = :etat')->setParameter('etat', $filtre['etat']);
        }
        if (isset($filtre['referentiel']) and $filtre['referentiel'] != '') {
            $qb = $qb->andWhere('referentiel.id = :referentiel')->setParameter('referentiel', $filtre['referentiel']);
        }
        if (isset($filtre['codefonction']) and $filtre['codefonction'] != '') {
            $qb = $qb->andWhere('codeFonction.id = :codefonction')->setParameter('codefonction', $filtre['codefonction']);
        }
        if (isset($filtre['famille']) and $filtre['famille'] != '') {
            $qb = $qb->andWhere('famille.id = :famille')->setParameter('famille', $filtre['famille']);
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $niveau
     * @return FicheMetier[]
     */
    public function getFichesMetiersWithNiveau(int $niveau): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveauxbas.niveau >= :niveau')
            ->andWhere('niveauxhaut.niveau <= :niveau')
            ->setParameter('niveau', $niveau)
            ->andWhere('ficheMetier.histoDestruction IS NULL')
            ->andWhere('etat.code = :ok')
            ->setParameter('ok', FicheMetierEtats::ETAT_VALIDE);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiersValides(string $order = 'id'): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etype.code = :ucode')
            ->setParameter('ucode', FicheMetierEtats::ETAT_VALIDE)
            ->orderBy('ficheMetier.', $order);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFicheMetier(?int $id): ?FicheMetier
    {
        $qb = $this->createQueryBuilder()
//            ->addSelect('fmactivite')->leftJoin('ficheMetier.activites', 'fmactivite')
//            ->addSelect('activite')->leftJoin('fmactivite.activite', 'activite')
//            ->addSelect('activite_libelle')->leftJoin('activite.libelles', 'activite_libelle')
//            ->addSelect('activite_dscription')->leftJoin('activite.descriptions', 'activite_dscription')
//            ->addSelect('aformation')->leftJoin('activite.formations', 'aformation')

            //APPLICATIONS - fiche et activités associées
//            ->addSelect('activite_applicationelement')->leftJoin('activite.applications', 'activite_applicationelement')
//            ->addSelect('activite_application')->leftJoin('activite_applicationelement.application', 'activite_application')
//            ->addSelect('activite_application_groupe')->leftJoin('activite_application.groupe', 'activite_application_groupe')
//            ->addSelect('fiche_applicationelement')->leftJoin('ficheMetier.applications', 'fiche_applicationelement')
//            ->addSelect('fiche_application')->leftJoin('fiche_applicationelement.application', 'fiche_application')
//            ->addSelect('fiche_application_niveau')->leftJoin('fiche_applicationelement.niveau', 'fiche_application_niveau')
//            ->addSelect('fiche_application_groupe')->leftJoin('fiche_application.groupe', 'fiche_application_groupe')

            //COMPETENCE - fiche et activités associées
//            ->addSelect('activite_competenceelement')->leftJoin('activite.competences', 'activite_competenceelement')
//            ->addSelect('activite_competence')->leftJoin('activite_competenceelement.competence', 'activite_competence')
//            ->addSelect('activite_competence_theme')->leftJoin('activite_competence.theme', 'activite_competence_theme')
//            ->addSelect('activite_competence_type')->leftJoin('activite_competence.type', 'activite_competence_type')
//            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
//            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
//            ->addSelect('fiche_competence_niveau')->leftJoin('fiche_competenceelement.niveau', 'fiche_competence_niveau')
//            ->addSelect('fiche_competence_theme')->leftJoin('fiche_competence.theme', 'fiche_competence_theme')
//            ->addSelect('fiche_competence_type')->leftJoin('fiche_competence.type', 'fiche_competence_type')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [" . $id . "].", 0, $e);
        }
        return $result;
    }

    public function getRequestedFicheMetier(AbstractController $controller, string $name = 'fiche', bool $notNull = false): ?FicheMetier
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if ($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [" . $ficheId . "]");

        return $fiche;
    }

    /** @return FicheMetier[] */
    public function getFicheByFamilleProfessionnelle(FamilleProfessionnelle $famille): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('famille = :famille')->setParameter('famille', $famille)
            ->orderBy('metier.libelle');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersAsOptionGroup(): array
    {
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        $options = [];

        foreach ($familles as $famille) {
            $optionsoptions = [];
            foreach ($this->getFicheByFamilleProfessionnelle($famille) as $fiche) {
                if ($fiche->estNonHistorise()) $optionsoptions[$fiche->getId()] = $fiche->getLibelle() . " (dernière modification " . $fiche->getHistoModification()->format("d/m/Y") . ")";
            }
            asort($optionsoptions);
            $array = [
                'label' => $famille->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

        return $options;
    }

    public function getFicheMetierByReferentielAndCode(Referentiel $referentiel, string $code): ?FicheMetier
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ficheMetier.referentiel = :referentiel')->setParameter('referentiel', $referentiel)
            ->andWhere('ficheMetier.reference = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [" . FicheMetier::class . "] partagent la même identification [" . $referentiel->getLibelleCourt() . "|" . $code . "]", -1, $e);
        }
        return $result;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiersByFamilleProfessionnelle(FamilleProfessionnelle $familleProfessionnelle): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('ficheMetier.familleProfessionnelle = :familleProfessionnelle')->setParameter('familleProfessionnelle', $familleProfessionnelle);
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function setDefaultValues(FicheMetier $fiche): FicheMetier
    {
        $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_REDACTION);
        $this->getConfigurationService()->addDefaultToFicheMetier($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getApplicationsDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        $dictionnaire = [];

        foreach ($fiche->getApplicationListe() as $applicationElement) {
            $application = ($asElement) ? $applicationElement : $applicationElement->getApplication();
            $dictionnaire[$application->getId()]["entite"] = $application;
            $dictionnaire[$application->getId()]["raison"][] = $fiche;
            $dictionnaire[$application->getId()]["conserve"] = true;
        }

//        foreach ($fiche->getMissions() as $mission) {
//            foreach ($mission->getMission()->getApplicationListe() as $applicationElement) {
//                $application = ($asElement)?$applicationElement:$applicationElement->getApplication();
//                $dictionnaire[$application->getId()]["entite"] = $application;
//                $dictionnaire[$application->getId()]["raison"][] = $mission;
//                $dictionnaire[$application->getId()]["conserve"] = true;
//            }
//        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getCompetencesDictionnairesByType(FicheMetier $fiche, CompetenceType $type, bool $asElement = false): array
    {
        //todo faire mieux en requetant correctement
        $listing = $fiche->getCompetenceListe();
        $listing = array_filter($listing, function (CompetenceElement $element) use ($type) {
            $competence = $element->getCompetence();
            return $competence->getType() === $type;
        });

        $dictionnaire = [];

        foreach ($listing as $competenceElement) {
            $competence = ($asElement) ? $competenceElement : $competenceElement->getCompetence();
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getCompetencesDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        $dictionnaire = [];

        foreach ($fiche->getCompetenceListe() as $competenceElement) {
            $competence = ($asElement) ? $competenceElement : $competenceElement->getCompetence();
            $dictionnaire[$competence->getId()]["entite"] = $competence;
            $dictionnaire[$competence->getId()]["raison"][] = $fiche;
            $dictionnaire[$competence->getId()]["conserve"] = true;
        }

        return $dictionnaire;
    }

    /**
     * @param FicheMetier $fiche
     * @param bool $asElement
     * @return array
     */
    public function getCompetencesSpecifiquesDictionnaires(FicheMetier $fiche, bool $asElement = false): array
    {
        return [];
    }

    /**
     * @param Competence $competence
     * @return FicheMetier[]
     */
    public function getFichesMetiersByCompetence(Competence $competence): array
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('fiche_competenceelement')->leftJoin('ficheMetier.competences', 'fiche_competenceelement')
            ->addSelect('fiche_competence')->leftJoin('fiche_competenceelement.competence', 'fiche_competence')
            ->andWhere('fiche_competenceelement.competence = :competence')
            ->setParameter('competence', $competence)
            ->orderBy('ficheMetier.libelle', 'ASC');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function dupliquerFicheMetier(FicheMetier $fiche): FicheMetier
    {
        $duplicata = new FicheMetier();
        //base
        $this->create($duplicata);

        //missions principales
        foreach ($fiche->getMissions() as $mission) {
            $activiteDuplicata = new MissionElement();
            $activiteDuplicata->setMission($mission->getMission());
            $activiteDuplicata->setPosition($mission->getPosition());
            $this->getObjectManager()->persist($activiteDuplicata);
            $this->getObjectManager()->flush($activiteDuplicata);
            $duplicata->addMission($mission);
        }

        //applications
        /** @var ApplicationElement $application */
        foreach ($fiche->getApplicationCollection() as $application) {
            $element = new ApplicationElement();
            $element->setApplication($application->getApplication());
            $element->setCommentaire($application->getCommentaire());
            $element->setClef($application->isClef());
            $this->getApplicationElementService()->create($element);
            if ($application->estHistorise()) {
                $this->getApplicationElementService()->historise($element);
                $this->getApplicationElementService()->update($element);
            }
            $duplicata->addApplicationElement($element);
        }

        //compétences
        /** @var CompetenceElement $competence */
        foreach ($fiche->getCompetenceCollection() as $competence) {
            $element = new CompetenceElement();
            $element->setCompetence($competence->getCompetence());
            $element->setCommentaire($competence->getCommentaire());
            $element->setClef($competence->isClef());
            $this->getCompetenceElementService()->create($element);
            if ($competence->estHistorise()) {
                $this->getCompetenceElementService()->historise($element);
                $this->getCompetenceElementService()->update($element);
            }
            $duplicata->addCompetenceElement($element);
        }

        //etat
        $this->getEtatInstanceService()->setEtatActif($duplicata, FicheMetierEtats::ETAT_REDACTION);
        $this->update($duplicata);

        return $duplicata;
    }

    public function readFromCSV($fichier_path): array
    {
        $handle = fopen($fichier_path, "r");

        $array = [];
        while ($content = fgetcsv($handle, 0, ";")) {
            $array[] = $content;
        }

        $code_index = array_search('Code emploi type', $array[0]);
        $code_libelle = $array[1][$code_index];
        $mission_index = array_search('Mission', $array[0]);
        $mission_libelle = $array[1][$mission_index];
        $activites_index = array_search('Activités principales', $array[0]);
        $activites_libelle = explode(FicheMetierService::REFERENS_SEP, $array[1][$activites_index]);

        $competences_index = array_search('COMPETENCES_ID', $array[0]);
        $competences_ids = explode(FicheMetierService::REFERENS_SEP, $array[1][$competences_index]);

        $referens = $this->getReferentielService()->getReferentielByLibelleCourt('REFERENS3');
        if ($referens === null) {
            throw new RuntimeException("<strong>Aucun référentiel de compétence [REFERENS].</strong><br>Celui-ci est nécessaire pour l'import de fiche CSV ReferensIII.");
        }
        $competences = [];
        $competencesListe = [];
        foreach ($competences_ids as $competence_id) {
            $competence = $this->getCompetenceService()->getCompetenceByRefentiel($referens, $competence_id);
            if ($competence !== null) {
                $competences[$competence->getType()->getLibelle()][$competence->getId()] = $competence;
                $competencesListe[$competence->getId()] = $competence;
            } else {
                $competences['Manquantes'][] = $competence_id;
            }
        }
        $applications = [];

        return [
            'code' => $code_libelle,
            'mission' => $mission_libelle,
            'activites' => $activites_libelle,
            'competences' => $competences,
            'competencesListe' => $competencesListe,
            'applications' => $applications,

        ];
    }

    public function exporter(?FicheMetier $fichemetier): string
    {
        $displayResume = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_RESUME);
        $displayCode = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);
        $displayRaison = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_RAISON);
        $displayMission = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_MISSION);
        $displayApplications = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_APPLICATION);
        $displayCompetences = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_COMPETENCE);
        $displayCompetencesSpecifiques = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_COMPETENCE_SPECIFIQUE);
        $displayContexte = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_CONTEXTE);
        $displayTendance = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_TENDANCE);

        $tendancesTypes = $this->getTendanceTypeService()->getTendancesTypes('ordre','asc',false);
        $fichemetier->setTendancesTypes($tendancesTypes);
        $thematiquesTypes = $this->getThematiqueTypeService()->getThematiquesTypes('ordre','asc',false);
        $fichemetier->setThematiquesTypes($thematiquesTypes);

        $vars = [
            'fichemetier' => $fichemetier,
            'MacroService' => $this->getMacroService(),
            'DISPLAY_RESUME' => $displayResume,
            'DISPLAY_CODEFONCTION' => $displayCode,
            'DISPLAY_RAISON' => $displayRaison,
            'DISPLAY_MISSION' => $displayMission,
            'DISPLAY_ACTIVITE' => $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::DISPLAY_ACTIVITE),
            'DISPLAY_APPLICATION' => $displayApplications,
            'DISPLAY_COMPETENCE' => $displayCompetences,
            'DISPLAY_COMPETENCE_SPECIFIQUE' => $displayCompetencesSpecifiques,
            'DISPLAY_CONTEXTE' => $displayContexte,
            'DISPLAY_TENDANCE' => $displayTendance,
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(PdfTemplate::FICHE_METIER, $vars);

        try {
            $exporter = new PdfExporter();
            $exporter->getMpdf()->SetTitle($rendu->getSujet());
            $exporter->setHeaderScript('');
            $exporter->setFooterScript('');
            $exporter->addBodyHtml($rendu->getCorps());
            return $exporter->export($rendu->getSujet());
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'export en PDF", 0, $e);
        }
    }

    public function getFichesMetiersByDiscipline(?CompetenceDiscipline $discipline): array
    {
        $qb = $this->createQueryBuilder()
            ->join('ficheMetier.competences', 'competenceElement')->addSelect('competenceElement')
            ->join('competenceElement.competence', 'competence')->addSelect('competence')
            ->leftjoin('competence.discipline', 'discipline')->addSelect('discipline')
            ->andWhere('ficheMetier.histoDestruction IS NULL')
            ->orderby('ficheMetier.libelle');
        $qb = $qb->andWhere('competence.discipline = :discipline')->setParameter('discipline', $discipline);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getFichesMetiersByDisciplines(array $disciplines): array
    {
        //note : si trop lent faire en dql ...
        $results = [];
        foreach ($disciplines as $discipline) {
            $results[$discipline->getId()] = $this->getFichesMetiersByDiscipline($discipline);
        }
        return $results;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiersByCodeFonction(string $strCodeFonction, bool $withHisto = false): array
    {
        //NB ici on décompose le code fonction ce qui suppose qu'il est correctement construit sinon cela va échouer
        // niveau de fonction 4 premieres lettres
        $codeNiveauFonction = substr($strCodeFonction, 0, 4);
        $niveauFonction = $this->getNiveauFonctionService()->getNiveauFonctionByCode($codeNiveauFonction);
        if ($niveauFonction === null) return [];

        // le reste correspond au code de la famille professionnelle
        $codeFamilleProfessionnelle = substr($strCodeFonction, 4);
        $familleProfessionnelle = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByCode($codeFamilleProfessionnelle);
        if ($familleProfessionnelle === null) return [];

        $codeFonction = $this->getCodeFonctionService()->getCodeFonctionByNiveauAndFamille($niveauFonction, $familleProfessionnelle);
        if ($codeFonction === null) return [];

        $qb = $this->createQueryBuilder()
            ->andWhere('ficheMetier.codeFonction = :codeFonction')->setParameter('codeFonction', $codeFonction);
        if (!$withHisto) $qb = $qb->andWhere('ficheMetier.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;

    }

    /** @return FicheMetier[] */
    public function getFichesMetiersHavingActivite(Activite $activite): array
    {
        $qb = $this->createQueryBuilder();
        $qb = FicheMetier::decorateWithActivite($qb, 'ficheMetier', $activite);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiersHavingMissionPrincipale(Mission $mission): array
    {
        $qb = $this->createQueryBuilder();
        $qb = FicheMetier::decorateWithMissionPrincipale($qb, 'ficheMetier', $mission);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}