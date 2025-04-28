<?php

namespace FicheReferentiel\Service\Importer;

use DateTime;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheReferentiel\Entity\Db\FicheReferentiel;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;

class ImporterService
{
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use FicheReferentielServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    // COMPETENCES -----------------------------------------------------------------------------------------------------

    public function createCompetencesDgafp(array $listing, bool $persist): array
    {
        $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('DGAFP');
        if ($referentiel === null) throw new RuntimeException("Aucun référentiel de compétence pour le direction générale de l'aldministation et de la fonction public [code:DGFAP]");
        $allCompetences = $referentiel->getCompetences();
        $maximum = 0; foreach ($allCompetences as $item) { $maximum = max($maximum, $item->getId()); }

        $dictionnaires = [];
        $nouvelles = [];

        foreach ($listing as $typeId => $competencesLibelles) {
            $type = $this->getCompetenceTypeService()->getCompetenceType($typeId);
            sort($competencesLibelles);
            $competencesLibelles = array_unique($competencesLibelles);
            foreach ($competencesLibelles as $competenceLibelle) {
                $competenceLibelle = trim($competenceLibelle);
                $competence = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($referentiel, $competenceLibelle);
                if ($competence === null) {
                    $maximum++;
                    $competence = $this->getCompetenceService()->createWith($competenceLibelle, null, $type, null, $referentiel, $maximum, $persist);
                    $nouvelles[] = $competence;
                }
                $dictionnaires[$type->getLibelle()][$competenceLibelle] = $competence;
            }
        }

        return [
            'dictionnaires' => $dictionnaires,
            'nouvelles' => $nouvelles,
        ];
    }

    // les compétences ne sont pas sauvegradées car devraient être importées au préalable
    public function createCompetencesReferens(array $competencesIds): array
    {
        $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('REFERENS');
        if ($referentiel === null) throw new RuntimeException("Aucun référentiel de compétence [code:REFERENS]");

        $dictionnaires = [];
        $manquantes = [];

        foreach ($competencesIds as $competenceId) {
            $competence = $this->getCompetenceService()->getCompetenceByRefentiel($referentiel, $competenceId);
            if ($competence === null) {
                $manquantes[$competenceId] = $competenceId;
            }
            $dictionnaires[$competenceId] = $competence;
        }

        return [
            'dictionnaires' => $dictionnaires,
            'manquantes' => $manquantes,
        ];
    }

    // METIERS ---------------------------------------------------------------------------------------------------------

    public function createMetier(array $listing, string $referentielCode, bool $persist): array
    {
        $referentiel = $this->getReferentielService()->getReferentielByCode($referentielCode); //todo param ?
        if ($referentiel === null) throw new RuntimeException("Aucun référentiel de métier n'a été trouvé avec le code [".$referentielCode."]");

        $dictionnaires = [];
        $nouveaux = [];

        // retrait de doublons éventuels
        $tlisting = [];
        foreach ($listing as $item) {
            if (!isset($tlisting[$item["code"]])) $tlisting[$item["code"]] = $item;
        }

        foreach ($tlisting as $metierData) {
            $metier = $this->getMetierService()->getMetierByReference($referentielCode, $metierData["code"]);
            if ($metier === null) {
                $metier = $this->getMetierService()->createWith($metierData["libelle"], "DGAFP", $metierData["code"], $metierData["domaine"], $persist);
                $nouveaux[$metierData["code"]] = $metier;
            }
            $dictionnaires[$metierData["code"]] = $metier;
        }

        return [
            'dictionnaires' => $dictionnaires,
            'nouveaux' => $nouveaux,
        ];
    }

    // FICHE -----------------------------------------------------------------------------------------------------------

    public function createFicheReferentielDgafp(array $listing, array $competenceDictionnaire, array $metierDictionnaire, bool $persist): array
    {
        $referentiel = $this->getReferentielService()->getReferentielByCode('DGAFP'); //todo param ?
        if ($referentiel === null) throw new RuntimeException("Aucun référentiel de métier pour le direction générale de l'aldministation et de la fonction public [code:DGFAP]");


        $dictionnaires = [];
        $nouvelles = [];

        $types = $this->getCompetenceTypeService()->getCompetencesTypes();
        foreach ($listing as $item) {
            //fiche
            $ficheReferentiel = new FicheReferentiel();
            $ficheReferentiel->setReferentiel($referentiel);
            $ficheReferentiel->setMetier($metierDictionnaire[$item['code']]);
            //info
            $ficheReferentiel->setDefinitionSynthetique(is_array($item['definition'])?implode("\n",$item['definition']): $item['definition']);
            $ficheReferentiel->setCompetenceManageriale(is_array($item['managment'])?implode("\n", $item['managment']): $item['managment']);
            $ficheReferentiel->setActivite(is_array($item['activite'])?implode("\n",$item['activite']): $item['activite']);
            $ficheReferentiel->setConditionsParticulieres(is_array($item['conditions'])?implode("\n",$item['conditions']):$item['conditions']);
            $ficheReferentiel->setTendanceEvolution(is_array($item['tendance'])?implode("\n",$item['tendance']):$item['tendance']);
            $ficheReferentiel->setImpact(is_array($item['impact'])?implode("\n",$item['impact']):$item['impact']);
            $ficheReferentiel->setCodeCsp($item['codeCsp']);
            $ficheReferentiel->setFpt($item['fpt']);
            $ficheReferentiel->setFph($item['fph']);
            $ficheReferentiel->setFpe($item['fpe']);
            if ($persist) $this->getFicheReferentielService()->create($ficheReferentiel);
            //competence
            foreach ($types as $type) {
                if (isset($item[$type->getLibelle()])) {
                    foreach ($item[$type->getLibelle()] as $libelles) {
                        foreach ($libelles as $libelle) {
                            $competence = $competenceDictionnaire[$type->getLibelle()][$libelle];
                            $element = new CompetenceElement();
                            $element->setCompetence($competence);
                            $element->setCommentaire("Importation FicheReferentiel DGAFP[" . $item['code'] . "] du " . ((new DateTime())->format('d/m/Y H:i:s')));
                            if ($persist) $this->getCompetenceElementService()->create($element);
                            $ficheReferentiel->addCompetenceElement($element);
                        }
                    }
                }
            }
            if ($persist) $this->getFicheReferentielService()->update($ficheReferentiel);
            $dictionnaires[$item['code']] = $ficheReferentiel;
        }
        return [
            'dictionnaires' => $dictionnaires,
            'nouvelles' => $nouvelles,
        ];
    }


    public function createFicheReferentielReferens(array $listing, array $competenceDictionnaire, array $metierDictionnaire, bool $persist): array
    {
        $referentiel = $this->getReferentielService()->getReferentielByCode('REFERENS'); //todo param ?
        if ($referentiel === null) throw new RuntimeException("Aucun référentiel de métier pour le code [REFERENS]");

        $dictionnaires = [];
        $nouvelles = [];

        foreach ($listing as $item) {
            //fiche
            $ficheReferentiel = new FicheReferentiel();
            $ficheReferentiel->setReferentiel($referentiel);
            $ficheReferentiel->setMetier($metierDictionnaire[$item['code']]);
            //info
//            $ficheReferentiel->setDefinitionSynthetique(is_array($item['definition'])?implode("\n",$item['definition']): $item['definition']);
//            $ficheReferentiel->setCompetenceManageriale(is_array($item['managment'])?implode("\n", $item['managment']): $item['managment']);
            $ficheReferentiel->setActivite(is_array($item['activite'])?implode("\n",$item['activite']): $item['activite']);
            $ficheReferentiel->setConditionsParticulieres(is_array($item['conditions'])?implode("\n",$item['conditions']):$item['conditions']);
            $ficheReferentiel->setTendanceEvolution(is_array($item['tendance'])?implode("\n",$item['tendance']):$item['tendance']);
            $ficheReferentiel->setImpact(is_array($item['impact'])?implode("\n",$item['impact']):$item['impact']);
            $ficheReferentiel->setCorrespondanceStatutaire($item['correspondance']);
//            $ficheReferentiel->setFpt($item['fpt']);
//            $ficheReferentiel->setFph($item['fph']);
//            $ficheReferentiel->setFpe($item['fpe']);
            if ($persist) $this->getFicheReferentielService()->create($ficheReferentiel);
            //competence
            foreach ($item["competences"] as $competenceId) {
                $competence = $competenceDictionnaire[(int) $competenceId];
                if ($competence !== null) {
                    $element = new CompetenceElement();
                    $element->setCompetence($competence);
                    $element->setCommentaire("Importation FicheReferentiel DGAFP[" . $item['code'] . "] du " . ((new DateTime())->format('d/m/Y H:i:s')));
                    if ($persist) $this->getCompetenceElementService()->create($element);
                    $ficheReferentiel->addCompetenceElement($element);
                }
            }
            if ($persist) $this->getFicheReferentielService()->update($ficheReferentiel);
            $dictionnaires[$item['code']] = $ficheReferentiel;
        }
        return [
            'dictionnaires' => $dictionnaires,
            'nouvelles' => $nouvelles,
        ];
    }


}