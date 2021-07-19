<?php

namespace Application\Service\Niveau;

use Application\Entity\Db\Niveau;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class NiveauService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Niveau $niveau
     * @return Niveau
     */
    public function create(Niveau $niveau)
    {
        $this->createFromTrait($niveau);
        return $niveau;
    }

    /**
     * @param Niveau $niveau
     * @return Niveau
     */
    public function update(Niveau $niveau)
    {
        $this->updateFromTrait($niveau);
        return $niveau;
    }

    /**
     * @param Niveau $niveau
     * @return Niveau
     */
    public function historise(Niveau $niveau)
    {
        $this->historiserFromTrait($niveau);
        return $niveau;
    }

    /**
     * @param Niveau $niveau
     * @return Niveau
     */
    public function restore(Niveau $niveau)
    {
        $this->restoreFromTrait($niveau);
        return $niveau;
    }

    /**
     * @param Niveau $niveau
     * @return Niveau
     */
    public function delete(Niveau $niveau) : Niveau
    {
        $this->deleteFromTrait($niveau);
        return $niveau;
    }

    /** REQUETES **************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Niveau::class)->createQueryBuilder('niveau');
        return $qb;
    }

    /**
     * @param string $champs
     * @param string $ordre
     * @return Niveau[]
     */
    public function getNiveaux(string $champs = 'niveau', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('niveau.' . $champs, $ordre);
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getNiveauxAsOptions(string $champs = 'niveau', string $ordre = 'ASC') : array
    {
        $niveaux = $this->getNiveaux($champs, $ordre);
        $options = [];
        foreach ($niveaux as $niveau) {
            $options[$niveau->getId()] = "" . $niveau->getEtiquette() . " - " . $niveau->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return Niveau|null
     */
    public function getNiveau(?int $id) : ?Niveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('niveau.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Niveau partagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Niveau|null
     */
    public function getRequestedNiveau(AbstractActionController $controller, $param='niveau') : ?Niveau
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getNiveau($id);
        return $result;
    }
}

