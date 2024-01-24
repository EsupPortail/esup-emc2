<?php

namespace FicheMetier\Service\ThematiqueElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Niveau;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\ThematiqueElement;
use FicheMetier\Entity\Db\ThematiqueType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ThematiqueElementService
{
    use ProvidesObjectManager;
    
    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ThematiqueElement $thematiqueElement): ThematiqueElement
    {
        $this->getObjectManager()->persist($thematiqueElement);
        $this->getObjectManager()->flush($thematiqueElement);
        return $thematiqueElement;
    }

    public function update(ThematiqueElement $thematiqueElement): ThematiqueElement
    {
        $this->getObjectManager()->flush($thematiqueElement);
        return $thematiqueElement;
    }

    public function historise(ThematiqueElement $thematiqueElement): ThematiqueElement
    {
        $thematiqueElement->historiser();
        $this->getObjectManager()->flush($thematiqueElement);
        return $thematiqueElement;
    }

    public function restore(ThematiqueElement $thematiqueElement): ThematiqueElement
    {
        $thematiqueElement->dehistoriser();
        $this->getObjectManager()->flush($thematiqueElement);
        return $thematiqueElement;
    }

    public function delete(ThematiqueElement $thematiqueElement): ThematiqueElement
    {
        $this->getObjectManager()->remove($thematiqueElement);
        $this->getObjectManager()->flush($thematiqueElement);
        return $thematiqueElement;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ThematiqueElement::class)->createQueryBuilder('thematiqueelement')
            ->leftJoin('thematiqueelement.type', 'thematiqueType')->addSelect('thematiqueType')
            ->leftJoin('thematiqueelement.ficheMetier', 'ficheMetier')->addSelect('ficheMetier')
        ;
        return $qb;
    }

    public function getThematiqueElement(?int $id): ?ThematiqueElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('thematiqueelement.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ThematiqueElement::class."] partagent le même id [".$id."]");
        }
        return $result;
    }

    public function getRequestedThematiqueElement(AbstractActionController $controller, string $param='thematique-element'): ?ThematiqueElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getThematiqueElement($id);
    }

    /** @return ThematiqueElement[] */
    public function getThematiquesElements(string $champ='histoCreation', string $ordre='DESC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('thematiqueelement.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('thematiqueelement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return ThematiqueElement[] */
    public function getThematiquesElementsByFicheMetier(FicheMetier $ficheMetier, string $champ='histoCreation', string $ordre='DESC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('thematiqueelement.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->orderBy('thematiqueelement.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('thematiqueelement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getThematiqueElementByFicheMetierAndThematiqueType(FicheMetier $ficheMetier, ThematiqueType $thematiqueType): ?ThematiqueElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('thematiqueelement.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('thematiqueelement.type = :thematiqueType')->setParameter('thematiqueType', $thematiqueType)
            ->andWhere('thematiqueelement.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ThematiqueElement::class."] partagent [FicheMetier#".$ficheMetier->getId().",ThematiqueType#".$thematiqueType->getId()."]",0,$e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createOrUpdate(FicheMetier $ficheMetier, ThematiqueType $thematiqueType, Niveau $niveau, ?string $complement): void
    {
        $element = $this->getThematiqueElementByFicheMetierAndThematiqueType($ficheMetier, $thematiqueType);
        if ($element === null) {
            $element = new ThematiqueElement();
            $element->setFicheMetier($ficheMetier);
            $element->setType($thematiqueType);
            $element->setNiveauMaitrise($niveau);
            $element->setComplement($complement);
            $this->create($element);
        } else {
            $element->setNiveauMaitrise($niveau);
            $element->setComplement($complement);
            $this->update($element);
        }
    }
}