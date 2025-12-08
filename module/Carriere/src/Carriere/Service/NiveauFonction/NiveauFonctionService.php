<?php

namespace Carriere\Service\NiveauFonction;

use Carriere\Entity\Db\NiveauFonction;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\CodeEmploiType;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class NiveauFonctionService
{
    use ProvidesObjectManager;

    /** GESTION DE L'ENTITE *******************************************************************************************/

    public function create(NiveauFonction $fonctionType): void
    {
        $this->getObjectManager()->persist($fonctionType);
        $this->getObjectManager()->flush($fonctionType);
    }

    public function update(NiveauFonction $fonctionType): void
    {
        $this->getObjectManager()->flush($fonctionType);
    }

    public function historise(NiveauFonction $fonctionType): void
    {
        $fonctionType->historiser();
        $this->getObjectManager()->flush($fonctionType);
    }

    public function restore(NiveauFonction $fonctionType): void
    {
        $fonctionType->dehistoriser();
        $this->getObjectManager()->flush($fonctionType);
    }

    public function delete(NiveauFonction $fonctionType): void
    {
        $this->getObjectManager()->remove($fonctionType);
        $this->getObjectManager()->flush($fonctionType);
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(NiveauFonction::class)->createQueryBuilder('niveaufonction');
        return $qb;
    }

    public function getNiveauFonction(?int $id): ?NiveauFonction
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveaufonction.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".NiveauFonction::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedNiveauFonction(AbstractActionController $controller, string $param='niveau-fonction'): ?NiveauFonction
    {
        $id = $controller->params()->fromRoute($param);
        $resutl = $this->getNiveauFonction($id);
        return $resutl;
    }

    /** @return NiveauFonction[] */
    public function getNiveauxFonctions(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('niveaufonction.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getNiveauxFonctionsAsOptions(bool $withHisto = false): array
    {
        $result  = $this->getNiveauxFonctions($withHisto);
        $options = [];
        foreach ($result as $niveauFonction) {
            $options[$niveauFonction->getId()] = $this->optionify($niveauFonction);
        }
        return $options;
    }

    public function getNiveauFonctionByCode(?string $code): ?NiveauFonction
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveaufonction.code = :code')->setParameter('code', $code)
            ->andWhere('niveaufonction.histoDestruction IS NULL')
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".NiveauFonction::class."] partagent le même code [".$code."] (Attention, cela tient compte du caractère historisé)",0,$e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function optionify(NiveauFonction $niveauFonction): array
    {
        $label = $niveauFonction->getLibelle();
        if ($niveauFonction->getCode()) {
            $label .= "  <code>".$niveauFonction->getCode() . "</code>";
        } else {
            $label .= "  <em style='color:grey'>Aucun code fonction</em>";
        }
        $this_option = [
            'value' => $niveauFonction->getId(),
            'attributes' => [
                'data-content' =>
                    $label
            ],
            'label' => $niveauFonction->getLibelle(),
        ];
        return $this_option;
    }

    public function isCodeDisponible(NiveauFonction $type, ?string $code = null): bool
    {
        $result = $this->getNiveauFonctionByCode($type->getCode()??$code);
        if ($result === null) return true;
        if ($result === $type) return true;
        return false;
    }

}