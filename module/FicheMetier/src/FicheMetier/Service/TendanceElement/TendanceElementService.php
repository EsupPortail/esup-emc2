<?php

namespace FicheMetier\Service\TendanceElement;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\TendanceElement;
use FicheMetier\Entity\Db\TendanceType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class TendanceElementService
{
    use ProvidesObjectManager;
    
    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(TendanceElement $tendanceElement): TendanceElement
    {
        $this->getObjectManager()->persist($tendanceElement);
        $this->getObjectManager()->flush($tendanceElement);
        return $tendanceElement;
    }

    public function update(TendanceElement $tendanceElement): TendanceElement
    {
        $this->getObjectManager()->flush($tendanceElement);
        return $tendanceElement;
    }

    public function historise(TendanceElement $tendanceElement): TendanceElement
    {
        $tendanceElement->historiser();
        $this->getObjectManager()->flush($tendanceElement);
        return $tendanceElement;
    }

    public function restore(TendanceElement $tendanceElement): TendanceElement
    {
        $tendanceElement->dehistoriser();
        $this->getObjectManager()->flush($tendanceElement);
        return $tendanceElement;
    }

    public function delete(TendanceElement $tendanceElement): TendanceElement
    {
        $this->getObjectManager()->remove($tendanceElement);
        $this->getObjectManager()->flush($tendanceElement);
        return $tendanceElement;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(TendanceElement::class)->createQueryBuilder('tendanceelement')
            ->leftJoin('tendanceelement.type', 'tendancetype')->addSelect('tendancetype')
            ->leftJoin('tendanceelement.ficheMetier', 'ficheMetier')->addSelect('ficheMetier')
        ;
        return $qb;
    }

    public function getTendanceElement(?int $id): ?TendanceElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tendanceelement.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".TendanceElement::class."] partagent le même id [".$id."]");
        }
        return $result;
    }

    public function getRequestedTendanceElement(AbstractActionController $controller, string $param='tendance-element'): ?TendanceElement
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getTendanceElement($id);
    }

    /** @return TendanceElement[] */
    public function getTendancesElements(string $champ='histoCreation', string $ordre='DESC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('tendanceelement.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('tendanceelement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return TendanceElement[] */
    public function getTendancesElementsByFicheMetier(FicheMetier $ficheMetier, string $champ='histoCreation', string $ordre='DESC', bool $histo=false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tendanceelement.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->orderBy('tendanceelement.' . $champ, $ordre);

        if (!$histo) $qb = $qb->andWhere('tendanceelement.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getTendanceElementByFicheMetierAndTendanceType(FicheMetier $ficheMetier, TendanceType $tendanceType): ?TendanceElement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('tendanceelement.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $ficheMetier)
            ->andWhere('tendanceelement.type = :tendanceType')->setParameter('tendanceType', $tendanceType)
            ->andWhere('tendanceelement.histoDestruction IS NULL');

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".TendanceElement::class."] partagent [FicheMetier#".$ficheMetier->getId().",TendanceType#".$tendanceType->getId()."]",0,$e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function createOrUpdate(FicheMetier $ficheMetier, TendanceType $tendanceType, ?string $texte): void
    {
        $element = $this->getTendanceElementByFicheMetierAndTendanceType($ficheMetier, $tendanceType);
        if ($element === null) {
            $element = new TendanceElement();
            $element->setFicheMetier($ficheMetier);
            $element->setType($tendanceType);
            $element->setTexte($texte);
            $this->create($element);
        } else {
            $element->setTexte($texte);
            $this->update($element);
        }
    }
}