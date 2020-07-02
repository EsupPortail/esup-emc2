<?php

namespace Application\Service\FicheMetierEtat;

use Application\Entity\Db\FicheMetierEtat;
use Application\Service\RendererAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FicheMetierEtatService {
    use EntityManagerAwareTrait;
    use RendererAwareTrait;

    /** ENTITE ********************************************************************************************************/

    /**
     * @param FicheMetierEtat $etat
     * @return FicheMetierEtat
     */
    public function create(FicheMetierEtat $etat)
    {
        try {
            $this->getEntityManager()->persist($etat);
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.",0,$e);
        }
        return $etat;
    }

    /**
     * @param FicheMetierEtat $etat
     * @return FicheMetierEtat
     */
    public function update(FicheMetierEtat $etat)
    {
        try {
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.",0,$e);
        }
        return $etat;
    }

    /**
     * @param FicheMetierEtat $etat
     * @return FicheMetierEtat
     */
    public function delete(FicheMetierEtat $etat)
    {
        try {
            $this->getEntityManager()->remove($etat);
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en BD.",0,$e);
        }
        return $etat;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetierEtat::class)->createQueryBuilder('etat')
            ;
        return $qb;

    }

    public function getEtats($champ='id', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('etat.' . $champ, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param FicheMetierEtat $etat
     * @return array
     */
    public function optionify(FicheMetierEtat $etat) {
        $res = $this->renderer->ficheMetierEtat($etat);

        $this_option = [
            'value' =>  $etat->getId(),
            'attributes' => [
                'data-content' => $res . "&nbsp;&nbsp;&nbsp;&nbsp;".$etat->getDescription(),
            ],
            'label' => $etat->getDescription(),
        ];
        return $this_option;
    }

    public function getEtatsAsOption()
    {
        /** @var FicheMetierEtat[] $etats */
        $etats = $this->getEtats();
        $array = [];
        foreach ($etats as $etat) {
            $option = $this->optionify($etat);
            $array[$etat->getId()] = $option;
        }
        return $array;
    }

    /**
     * @param $id
     * @return FicheMetierEtat
     */
    public function getEtat($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FicheMetierEtat partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FicheMetierEtat
     */
    public function getRequestedEtat($controller, $param = 'etat')
    {
        $id = $controller->params()->fromRoute($param);
        $etat = $this->getEtat($id);
        return $etat;
    }

    /**
     * @param string $code
     * @return FicheMetierEtat
     */
    public function getEtatByCode($code)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FicheMetierEtat partagent le même code [".$code."]");
        }
        return $result;
    }
}