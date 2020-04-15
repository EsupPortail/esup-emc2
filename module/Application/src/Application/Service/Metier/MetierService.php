<?php

namespace Application\Service\Metier;

use Application\Entity\Db\Metier;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MetierService {
//    use UserServiceAwareTrait;
//    use EntityManagerAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function create(Metier $metier)
    {
        $this->createFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function update(Metier $metier)
    {
        $this->updateFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function historise(Metier $metier)
    {
        $this->historiserFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function restore(Metier $metier)
    {
        $this->restoreFromTrait($metier);
        return $metier;
    }

    /**
     * @param Metier $metier
     * @return Metier
     */
    public function delete(Metier $metier)
    {
        $this->deleteFromTrait($metier);
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

    public function getMetiersTypesAsMultiOptions(bool $historiser = false)
    {
        /** @var Metier[] $metiers */
        $qb = $this->getEntityManager()->getRepository(Metier::class)->createQueryBuilder('metier')
            ->orderBy('metier.libelle', 'ASC');
        $metiers = $qb->getQuery()->getResult();

        $vide = [];
        $result = [];
        foreach ($metiers as $metier) {
            if ($historiser OR $metier->estNonHistorise())
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
