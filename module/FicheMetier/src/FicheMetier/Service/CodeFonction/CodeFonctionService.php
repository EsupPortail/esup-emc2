<?php

namespace FicheMetier\Service\CodeFonction;

use Carriere\Entity\Db\NiveauFonction;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\CodeFonction;
use Laminas\Mvc\Controller\AbstractActionController;
use Metier\Entity\Db\FamilleProfessionnelle;
use RuntimeException;

class CodeFonctionService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(CodeFonction $codeFonction): void
    {
        $codeFonction->setCode($codeFonction->computeCode());
        $this->getObjectManager()->persist($codeFonction);
        $this->getObjectManager()->flush($codeFonction);
    }

    public function update(CodeFonction $codeFonction): void
    {
        $codeFonction->setCode($codeFonction->computeCode());
        $this->getObjectManager()->flush($codeFonction);
    }

    public function historise(CodeFonction $codeFonction): void
    {
        $codeFonction->historiser();
        $this->getObjectManager()->flush($codeFonction);
    }

    public function restore(CodeFonction $codeFonction): void
    {
        $codeFonction->dehistoriser();
        $this->getObjectManager()->flush($codeFonction);
    }

    public function delete(CodeFonction $codeFonction): void
    {
        $this->getObjectManager()->remove($codeFonction);
        $this->getObjectManager()->flush($codeFonction);
    }

    /** QUERRY ********************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CodeFonction::class)->createQueryBuilder('codeFonction')
            ->leftjoin('codeFonction.niveauFonction', 'niveauFonction')->addSelect('niveauFonction')
            ->leftJoin('codeFonction.familleProfessionnelle', 'familleProfessionnelle')->addSelect('familleProfessionnelle')
            ->leftJoin('familleProfessionnelle.correspondance', 'correspondance')->addSelect('correspondance')
        ;
        return $qb;
    }

    public function getCodeFonction(?int $id): ?CodeFonction
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('codeFonction.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CodeFonction::class."] partagent le même id [".$id."]",-1,$e);
        }
        return $result;
    }

    public function getRequestedCodeFonction(AbstractActionController $controller, string $param='code-fonction'): ?CodeFonction
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getCodeFonction($id);
        return $result;
    }

    public function getCodeFonctionByNiveauAndFamille(?NiveauFonction $niveauFonction, ?FamilleProfessionnelle $familleProfessionnelle): ?CodeFonction
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('codeFonction.niveauFonction = :niveauFonction')->setParameter('niveauFonction', $niveauFonction)
            ->andWhere('codeFonction.familleProfessionnelle = :familleProfessionnelle')->setParameter('familleProfessionnelle', $familleProfessionnelle)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CodeFonction::class."] partagent les mêmes [Niveau:".$niveauFonction->getLibelle()."|Famille".$familleProfessionnelle->getLibelle()."]",-1,$e);
        }
        return $result;
    }

    /** @return CodeFonction[] */
    public function getCodesFonctions(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb->andWhere('codeFonction.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return CodeFonction[] */
    public function getCodesFonctionsByNiveauFonction(?NiveauFonction $niveauFonction, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('codeFonction.niveauFonction = :niveauFonction')->setParameter('niveauFonction', $niveauFonction);
        if (!$withHisto) $qb = $qb->andWhere('codeFonction.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return CodeFonction[] */
    public function getCodesFonctionsByFamilleProfessionnelle(?FamilleProfessionnelle $familleProfessionnelle, bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('codeFonction.familleProfessionnelle = :familleProfessionnelle')->setParameter('familleProfessionnelle', $familleProfessionnelle);
        if (!$withHisto) $qb = $qb->andWhere('codeFonction.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCodeFonctionByCode(?string $code): ?CodeFonction
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('concat(niveauFonction.code, correspondance.categorie, familleProfessionnelle.position) = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CodeFonction::class."] partagent le même code fonction [".$code."]",-1,$e);
        }
        return $result;
    }

    /** @return CodeFonction[] */
    public function generateDictionnaire(): array
    {
        $dictionnaire = [];
        $codesFonctions = $this->getCodesFonctions();
        foreach ($codesFonctions as $codeFonction) {
            $dictionnaire[$codeFonction->computeCode()] = $codeFonction;
        }
        return $dictionnaire;
    }
}
