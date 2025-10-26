<?php

namespace FicheMetier\Service\Import;

use Carriere\Entity\Db\Categorie;
use Carriere\Entity\Db\Correspondance;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Element\Entity\Db\Competence;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Entity\Db\Referentiel;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;

class ImportService
{

    use CategorieServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MetierServiceAwareTrait;

    /** @return Categorie[] */
    public function readCategorie(array $header, array $data, string $mode, array &$info, array &$warning, array &$error): array
    {
        /** @var Categorie[] $categories * */
        $categories = [];
        if (in_array("REFERENS_CATEGORIE_EMPLOI", $header)) {
            $categorieDictionnary = [];
            foreach ($data as $item) {
                $rawCategorie = $item["REFERENS_CATEGORIE_EMPLOI"];
                $categories = explode("|", $rawCategorie);
                foreach ($categories as $categorie) {
                    $categorieDictionnary[$categorie] = $categorie;
                }
            }
            foreach ($categorieDictionnary as $categorie) {
                $categories[$categorie] = $this->getCategorieService()->getCategorieByCode($categorie);
                if ($categories[$categorie] === null) {
                    $info[] = "Création de la catégorie [" . $categorie . "]";
                    $categories[$categorie] = $this->getCategorieService()->createWith($categorie, ($mode === 'import'));
                }
            }
        }

        return $categories;
    }

    /** @return Competence[] */
    public function readCompetence(array $header, array $data, string $mode, array &$info, array &$warning, array &$error): array
    {
        $competences = [];
        $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode("REFERENS3");
        $allCompetences = $this->getCompetenceService()->getCompetencesByRefentiel($referentiel);
        if ($referentiel === null) $error[] = "Le référentiel [REFERENS3] n'existe pas.";
        if (in_array("COMPETENCES_ID", $header)) {
            foreach ($data as $item) {
                $ids = explode("|", $item["COMPETENCES_ID"]);
                foreach ($ids as $id) {
                    $competence = $allCompetences[$id] ?? null;
                    if ($competence === null) $warning[] = "La compétence identifié [" . $id . "] n'est pas présente dans le référentiel [REFERENS]";
//                    else $competences[$id] = $competence;
                }
            }
        }
        return $allCompetences;
    }


    /** @return Correspondance[] */
    public function readCorrespondance(array $header, array $data, string $mode, array &$info, array &$warning, array &$error): array
    {
        $correspondances = [];
        if (in_array("Code de la branche d’activité professionnelle", $header)) {
            $correspondanceDictionnary = [];
            foreach ($data as $item) {
                $correspondance = [];
                $correspondance["code"] = $item["Code de la branche d’activité professionnelle"] ?? null;
                $correspondance["intitulé"] = $item["Branche d’activité professionnelle"] ?? null;
                if ($correspondance["code"]) $correspondanceDictionnary[$correspondance["code"]] = $correspondance;
            }
            foreach ($correspondanceDictionnary as $correspondance) {
                $correspondances[$correspondance["code"]] = $this->getCorrespondanceService()->getCorrespondanceByTypeAndCode("BAP", $correspondance["code"]);
                if ($correspondances[$correspondance["code"]] === null) {
                    $info[] = "Création de la correspondance [" . $correspondance["code"] . "|" . $correspondance["intitulé"] . "]";
                    $correspondances[$correspondance["code"]] = $this->getCorrespondanceService()->createWith("BAP", $correspondance["code"], $correspondance["intitulé"], $mode === 'import');
                }
            }
        }
        return $correspondances;
    }

    /** @return FamilleProfessionnelle[] */
    public function readFamilleProfessionnelle(array $header, array $data, string $mode, array &$info, array &$warning, array &$error): array
    {
        $famillesProfessionnelles = [];
        if (in_array("Famille d’activité professionnelle", $header)) {
            $familleDictionnary = [];
            foreach ($data as $item) {
                $rawFamilleProfessionnelle = $item["Famille d’activité professionnelle"];
                $familles = explode("|", $rawFamilleProfessionnelle);
                foreach ($familles as $famille) {
                    $familleDictionnary[$famille] = $famille;
                }
            }
            foreach ($familleDictionnary as $famille) {
                $famillesProfessionnelles[$famille] = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($famille);
                if ($famillesProfessionnelles[$famille] === null) {
                    $info[] = "Création de la famille professionnelle [" . $famille . "]";
                    $famillesProfessionnelles[$famille] = $this->getFamilleProfessionnelleService()->createWith($famille, ($mode === 'import'));
                }
            }
        }
        return $famillesProfessionnelles;
    }

    public function readMetier(array $header, array $data, string $mode, array $famillesProfessionnelles, array $correspondances, array $categories, Referentiel $referentiel, array &$info, array &$warning, array&$error): array
    {
        $metiers = [];
        $codeReferentiel = $referentiel->getLibelleCourt();
        if (in_array("Code emploi type", $header)) {
            foreach ($data as $item) {
                $code = $item["Code emploi type"] ?? null;
                $metier = $this->getMetierService()->getMetierByReference($codeReferentiel, $code);
                if ($metier === null)
                {
                    $intitule = $item["Intitulé de l’emploi type"] ?? null;
                    $metier = $this->getMetierService()->createWith($intitule, $codeReferentiel, $code, null, $mode === 'import');

                    if ($item["Famille d’activité professionnelle"]) {
                        $elements = explode("|", $item["Famille d’activité professionnelle"]);
                        foreach ($elements as $element) {
                            $metier->addFamillesProfessionnelles($famillesProfessionnelles[$element]);
                        }
                    }
                    if ($item["Code de la branche d’activité professionnelle"] and $correspondances[$item["Code de la branche d’activité professionnelle"]]) $metier->addCorrespondance($correspondances[$item["Code de la branche d’activité professionnelle"]]);
                    if ($item["REFERENS_CATEGORIE_EMPLOI"] and $categories[$item["REFERENS_CATEGORIE_EMPLOI"]]) $metier->setCategorie($categories[$item["REFERENS_CATEGORIE_EMPLOI"]]);
                    if ($mode === 'import') $this->getMetierService()->update($metier);
                }
                $metiers[$code] = $metier;
            }
        }
        return $metiers;
    }
}