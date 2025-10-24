<?php

namespace Element\Entity\Db\Traits;

use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;

trait HasApplicationCollectionTrait
{

    private Collection $applications;

    public function getApplicationCollection() : Collection
    {
        return $this->applications;
    }

    /** @return ApplicationElement[] */
    public function getApplicationListe(bool $avecHisto = false): array
    {
        $applications = [];
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($avecHisto)  $applications[$applicationElement->getApplication()->getId()] = $applicationElement;
            else {
                if  ($applicationElement->estNonHistorise() and $applicationElement->getApplication()->estNonHistorise()) $applications[$applicationElement->getApplication()->getId()] = $applicationElement;
            }
        }
        return $applications;
    }

    public function getApplicationDictionnaire() : array
    {
        $dictionnaire = [];
        foreach ($this->applications as $applicationElement) {
            $element = [];
            $element['entite'] = $applicationElement;
            $element['raison'][] = $this;
            $element['conserve'] = true;
            $dictionnaire[$applicationElement->getApplication()->getId()] = $element;
        }
        return $dictionnaire;
    }

    public function hasApplication(Application $application): ?ApplicationElement
    {
        /** @var ApplicationElement $applicationElement */
        foreach ($this->applications as $applicationElement) {
            if ($applicationElement->estNonHistorise() and $applicationElement->getApplication() === $application) return $applicationElement;
        }
        return null;
    }

    public function addApplicationElement(ApplicationElement $element) : void
    {
        $this->applications->add($element);
    }

    public function removeApplicationElement(ApplicationElement $element) : void
    {
        $this->applications->removeElement($element);
    }

    public function clearApplications() : void
    {
        $this->applications->clear();
    }

    /** Methode pour les macros ***************************************************************************************/

    public function toArrayApplications(array $parameters = []): string
    {
        $on = $parameters[0]??true;
        if ($on === false)  return "";

        $applications = $this->getApplicationListe();
        usort($applications, function (ApplicationElement $a, ApplicationElement $b) {
            $aa = $a->getApplication(); $ba = $b->getApplication();
            $aat = $aa->getTheme()?$aa->getTheme()->getOrdre():PHP_INT_MAX; $bat = $ba->getTheme()?$ba->getTheme()->getOrdre():PHP_INT_MAX;
            if ($aat !== $bat) return $aat <=> $bat;
            return $aa->getLibelle() <=> $ba->getLibelle();
        });
        if (empty($applications))  return "";

        $html = <<<EOS
<h2>Applications</h2>

<table style='width:100%; border-collapse: collapse;'>
<thead>
    <tr>
        <th> Nom de l'application </th>
        <th> Thème </th>
        <th> Niveau de maîtrice</th>
        <th> Clef </th>
    </tr>
</thead>
<tbody>
EOS;

        foreach ($applications as $application) {
            $app = $application->getApplication();
            $html .= "<tr>";
            $html .= "<td>" . $app->getLibelle() . "</td>";
            $html .= "<td>" . ($app->getTheme()?$app->getTheme()->getLibelle():"Sans thème") . "</td>";
            $html .= "<td>" . ($application->getNiveauMaitrise()?$application->getNiveauMaitrise()->getLibelle():"Non précisé") . "</td>";
            $html .= "<td>" . ($application->isClef()?"Application clef":"") . "</td>";
            $html .= "</tr>";
        }

        $html .= <<<EOS
</tbody>
</table>
EOS;

        return $html;
    }
}