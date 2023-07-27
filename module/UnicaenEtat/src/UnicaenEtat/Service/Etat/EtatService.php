<?php

namespace UnicaenEtat\Service\Etat;

use Application\Service\RendererAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenEtat\Entity\Db\Etat;
use UnicaenEtat\Entity\Db\EtatType;
use Laminas\Mvc\Controller\AbstractActionController;

class EtatService {
    use EntityManagerAwareTrait;
    use RendererAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function create(Etat $etat) : Etat
    {
        try {
            $this->getEntityManager()->persist($etat);
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function update(Etat $etat) : Etat
    {
        try {
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function historise(Etat $etat) : Etat
    {
        try {
            $etat->historiser();
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function restore(Etat $etat) : Etat
    {
        try {
            $etat->dehistoriser();
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $etat;
    }

    /**
     * @param Etat $etat
     * @return Etat
     */
    public function delete(Etat $etat) : Etat
    {
        try {
            $this->getEntityManager()->remove($etat);
            $this->getEntityManager()->flush($etat);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $etat;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        //todo ajouter la jointure sur les actions une fois celles-ci en place ...
        $qb = $this->getEntityManager()->getRepository(Etat::class)->createQueryBuilder('etat')
            ->addSelect('etype')->join('etat.type', 'etype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return Etat[]
     */
    public function getEtats($champ = 'code', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
//            ->orderBy('etat.' . $champ, $ordre)
            ->orderBy('etat.type, etat.ordre', 'ASC')
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param int|null $id
     * @return Etat|null
     */
    public function getEtat(?int $id) : ?Etat
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Etat partagent le même id [".$id."].");
        }
        return $result;
    }

    /**
     * @param string $code
     * @return Etat
     */
    public function getEtatByCode(string $code)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :code')
            ->setParameter('code', $code)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Etat partagent le même code [".$code."].");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Etat
     */
    public function getRequestedEtat(AbstractActionController $controller, $param='etat')
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getEtat($id);
    }

    /**
     * @param EtatType $type
     * @return Etat[]
     */
    public function getEtatsByType(EtatType $type) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.type = :type')
            ->setParameter('type', $type)
            ->orderBy('etat.ordre', 'ASC')
        ;
        return $qb->getQuery()->getResult();
    }

    /**
     * @param string $code
     * @return Etat[]
     */
    public function getEtatsByTypeCode(string $code) : array
    {
        $qb = $this->createQueryBuilder()
            ->join('etat.type', 'type')->addSelect('type')
            ->andWhere('type.code = :code')
            ->setParameter('code', $code)
            ->orderBy('etat.ordre', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();

        $dictionnaire = [];
        foreach ($result as $etat) {
            $dictionnaire[$etat->getCode()] = $etat;
        }
        return $dictionnaire;
    }


    public function getEtatsAsOption(?EtatType $type = null)
    {
        $etats = [];
        if ($type === null) {
            $etats = $this->getEtats();
        } else {
            $etats = $this->getEtatsByType($type);
        }

        $options = [];
        foreach ($etats as $etat) {
            $options[$etat->getId()] = $this->optionify($etat);
        }
        return $options;
    }

    /**
     * @param Etat $etat
     * @return array
     */
    public function optionify(Etat $etat) {
        $res = $this->renderer->etatbadge($etat);

        $this_option = [
            'value' =>  $etat->getId(),
            'attributes' => [
                'data-content' => $res . "&nbsp;&nbsp;&nbsp;&nbsp;".$etat->getLibelle(),
            ],
            'label' => $etat->getLibelle(),
        ];
        return $this_option;
    }
}