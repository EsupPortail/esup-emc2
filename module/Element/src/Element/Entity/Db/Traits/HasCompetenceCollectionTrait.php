<?php

namespace Element\Entity\Db\Traits;

use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Doctrine\Common\Collections\ArrayCollection;
use Element\Entity\Db\CompetenceType;

trait HasCompetenceCollectionTrait {

    /** @var ArrayCollection */
    private $competences;

    public function getCompetenceCollection()
    {
        return $this->competences;
    }

    /** @return CompetenceElement[] */
    public function getCompetenceListe(bool $avecHisto = false) : array
    {
        $competences = [];
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($avecHisto OR $competenceElement->estNonHistorise()) $competences[$competenceElement->getCompetence()->getId()] = $competenceElement;
        }
        return $competences;
    }

    public function getCompetenceDictionnaire()
    {
        $dictionnaire = [];
        foreach ($this->competences as $competenceElement) {
            $element = [];
            $element['entite'] = $competenceElement;
            $element['raison'][] = $this;
            $element['conserve'] = true;
            $dictionnaire[$competenceElement->getCompetence()->getId()] = $element;
        }
        return $dictionnaire;
    }

    public function hasCompetence(Competence $competence) : bool
    {
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competences as $competenceElement) {
            if ($competenceElement->estNonHistorise() AND $competenceElement->getCompetence() === $competence) return true;
        }
        return false;
    }

    public function addCompetenceElement(CompetenceElement $element) : void
    {
        $this->competences->add($element);
    }

    public function removeCompetenceElement(CompetenceElement $element) : void
    {
        $this->competences->removeElement($element);
    }

    public function clearCompetences() : void
    {
        $this->competences->clear();
    }

    /** Methode pour les macros ***************************************************************************************/

    public function toArrayCompetences(): string
    {
        //recupération des types présents dans la collection
        $competences = $this->getCompetenceListe();
        $types = [];
        $dictionnaires = [];
        foreach ($competences as $competence) {
            $type = $competence->getCompetence()->getType();
            if ($type->getCode() !== CompetenceType::CODE_SPECIFIQUE) {
                $types[$type->getId()] = $competence->getCompetence()->getType();
                $dictionnaires[$type->getId()][] = $competence;
            }
        }

        if (empty($types)) return "";

        usort($types, function (CompetenceType $a, CompetenceType $b) {
            if ($a->getOrdre() !== $b->getOrdre()) return $a->getOrdre() <=> $b->getOrdre();
            return $a->getLibelle() <=> $b->getLibelle();
        });

        $html = "";
        foreach ($types as $type) {
            if (!empty($dictionnaires[$type->getId()])) {
                usort($dictionnaires[$type->getId()], function (CompetenceElement $a, CompetenceElement $b) {
                    $aa = $a->getCompetence();
                    $ba = $b->getCompetence();
                    $aat = $aa->getTheme() ? $aa->getTheme()->getLibelle() : "ZZZZ";
                    $bat = $ba->getTheme() ? $ba->getTheme()->getLibelle() : "ZZZZ";
                    if ($aat !== $bat) return $aat <=> $bat;
                    return $aa->getLibelle() <=> $ba->getLibelle();
                });
                $html .= "<h3>" . $type->getLibelle() . "</h3>";
                $html .= <<<EOS

<table style='width:100%; border-collapse: collapse;'>
<thead>
    <tr>
        <th> Compétences </th>
        <th style="width: 21rem;"> Thème </th>
        <th style="width: 11rem;"> Niveau de maîtrise</th>
        <th style="width: 2rem;"> Clé </th>
    </tr>
</thead>
<tbody>
EOS;

                foreach ($dictionnaires[$type->getId()] as $competence) {
                    $app = $competence->getCompetence();
                    $html .= "<tr>";
                    $html .= "<td>" . $app->getLibelle() . "</td>";
                    $html .= "<td>" . ($app->getTheme()?$app->getTheme()->getLibelle():"Sans thème") . "</td>";
                    $html .= "<td>" . ($competence->getNiveauMaitrise()?$competence->getNiveauMaitrise()->getLibelle():"Non précisé") . "</td>";
                    $html .= "<td>" . ($competence->isClef()?"Oui":"") . "</td>";
                    $html .= "</tr>";
                }

                $html .= <<<EOS
</tbody>
</table>
EOS;
            }

        }
        return $html;
    }

    public function getCompetencesStandard(): array
    {
        $competences = $this->getCompetenceListe();
        $dictionnaires = [];
        foreach ($competences as $competence) {
            $type = $competence->getCompetence()->getType();
            if ($type->getCode() !== CompetenceType::CODE_SPECIFIQUE) {
                $dictionnaires[] = $competence;
            }
        }
        return $dictionnaires;
    }

    public function getCompetencesSpecifiques(): array
    {
        $competences = $this->getCompetenceListe();
        $dictionnaires = [];
        foreach ($competences as $competence) {
            $type = $competence->getCompetence()->getType();
            if ($type->getCode() === CompetenceType::CODE_SPECIFIQUE) {
                $dictionnaires[] = $competence;
            }
        }
        return $dictionnaires;
    }

    public function toArrayCompetencesSpecifiques(): string
    {

        //recupération des types présents dans la collection
        $competences = $this->getCompetenceListe();
        $dictionnaires = [];
        $discplines = [];
        foreach ($competences as $competence) {
            $type = $competence->getCompetence()->getType();
            $discpline = $competence->getCompetence()->getDiscipline();
            $discplines[$discpline?$discpline->getId():PHP_INT_MAX] = $discpline;
            if ($type->getCode() === CompetenceType::CODE_SPECIFIQUE) {
                $dictionnaires[$discpline?$discpline->getId():PHP_INT_MAX][] = $competence;
            }
        }

        if (empty($dictionnaires)) return "";

        $html = "";

        foreach ($discplines as $discpline) {

            if (!empty($dictionnaires[$discpline?$discpline->getId():PHP_INT_MAX])) {
            $html .= "<h3>" . ($discpline?$discpline->getLibelle():"Sans discipline") . "</h3>";

            usort($dictionnaires[$discpline?$discpline->getId():PHP_INT_MAX], function (CompetenceElement $a, CompetenceElement $b) {
                $aa = $a->getCompetence();
                $ba = $b->getCompetence();
                $aat = $aa->getTheme() ? $aa->getTheme()->getLibelle() : "ZZZZ";
                $bat = $ba->getTheme() ? $ba->getTheme()->getLibelle() : "ZZZZ";
                if ($aat !== $bat) return $aat <=> $bat;
                return $aa->getLibelle() <=> $ba->getLibelle();
            });
            $html .= <<<EOS
<table style='width:100%; border-collapse: collapse;'>
<thead>
    <tr>
        <th> Compétences </th>
        <th style="width: 21rem;"> Thème </th>
        <th style="width: 11rem;"> Niveau de maîtrise</th>
        <th style="width: 2rem;"> Clé </th>
    </tr>
</thead>
<tbody>
EOS;

            foreach ($dictionnaires[$discpline?$discpline->getId():PHP_INT_MAX] as $competence) {
                $app = $competence->getCompetence();
                $html .= "<tr>";
                $html .= "<td>" . $app->getLibelle() . "</td>";
                $html .= "<td>" . ($app->getTheme() ? $app->getTheme()->getLibelle() : "Sans thème") . "</td>";
                $html .= "<td>" . ($competence->getNiveauMaitrise() ? $competence->getNiveauMaitrise()->getLibelle() : "Non précisé") . "</td>";
                $html .= "<td>" . ($competence->isClef() ? "Oui" : "") . "</td>";
                $html .= "</tr>";
            }

            $html .= <<<EOS
</tbody>
</table>
EOS;
        }
        }

        return $html;
    }
}