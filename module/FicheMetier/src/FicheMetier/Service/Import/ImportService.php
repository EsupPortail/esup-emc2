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
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class ImportService
{

    use CategorieServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

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
        $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode("REFERENS");
        if ($referentiel === null) $error[] = "Le référentiel [REFERENS] n'existe pas.";
        if (in_array("COMPETENCES_ID", $header)) {
            foreach ($data as $item) {
                $ids = explode("|", $item["COMPETENCES_ID"]);
                foreach ($ids as $id) {
                    $competence = $this->getCompetenceService()->getCompetenceByRefentielAndId($referentiel, $id);
                    if ($competence === null) $warning[] = "La compétence identifié [" . $id . "] n'est pas présente dans le référentiel [REFERENS]";
                    else $competences[$id] = $competence;
                }
            }
        }
        return $competences;
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
}