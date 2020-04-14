<?php

namespace Application\Service\Metier;

use Application\Entity\Db\Metier;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MetierService {
    use EntityManagerAwareTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function create($metier)
    {
        try {
            $this->getEntityManager()->persist($metier);
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la création d'un Metier", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update($metier)
    {
        try {
            $this->getEntityManager()->flush($metier);
        } catch (ORMException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la mise à jour d'un Metier.", $e);
        }
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete($metier)
    {
        try {
            $this->getEntityManager()->remove($metier);
            $this->getEntityManager()->flush();
        } catch (ORMException $e) {
            throw  new RuntimeException("Un problème s'est produit lors de la suppression d'un Metier", $e);
        }
        return $metier;
    }

    /** REQUETAGES ****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    private function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->addSelect('domaine')->leftJoin('metier.domaine','domaine')
            ->addSelect('famille')->leftJoin('domaine.famille','famille')
            ->addSelect('fichemetier')->leftJoin('metier.fichesMetiers', 'fichemetier')
        ;
        return $qb;
    }

    /**
     * @return Metier[]
     */
    public function getMetiers()
    {
        $qb = $this->createQueryBuilder()
            ->addOrderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return Metier
     */
    public function getMetier($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('metier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Metier partagent le même identifiant [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Metier
     */
    public function getRequestedMetier($controller, $paramName = 'metier')
    {
        $id = $controller->params()->fromRoute($paramName);
        $metier = $this->getMetier($id);

        return $metier;
    }

    public function getMetiersTypesAsMultiOptions()
    {
        /** @var Metier[] $metiers */
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->orderBy('metier.libelle', 'ASC');
        $metiers = $qb->getQuery()->getResult();

        $vide = [];
        $result = [];
        foreach ($metiers as $metier) {
            if ($metier->getDomaine()) {
                $result[$metier->getDomaine()->getLibelle()][] = $metier;
            } else {
                $vide[] = $metier;
            }
        }
        ksort($result);
        $multi = [];
        foreach ($result as $key => $metiers) {
            //['label'=>'A', 'options' => ["A" => "A", "a"=> "a"]],
            $options = [];
            foreach ($metiers as $metier) {
                $options[$metier->getId()] = $metier->getLibelle();
            }
            $multi[] = ['label' => $key, 'options' => $options];
        }
        $options = [];
        foreach ($vide as $metier) {
            $options[$metier->getId()] = $metier->getLibelle();
        }
        $multi[] = ['label' => 'Sans domaine rattaché', 'options' => $options];
        return $multi;

    }
}
