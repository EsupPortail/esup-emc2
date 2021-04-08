<?php

namespace Application\Service\MaitriseNiveau;

use Application\Entity\Db\MaitriseNiveau;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use UnicaenUtilisateur\Service\GestionEntiteHistorisationTrait;
use Zend\Mvc\Controller\AbstractActionController;

class MaitriseNiveauService {
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param MaitriseNiveau $maitrise
     * @return MaitriseNiveau
     */
    public function create(MaitriseNiveau $maitrise) : MaitriseNiveau
    {
        $this->createFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param MaitriseNiveau $maitrise
     * @return MaitriseNiveau
     */
    public function update(MaitriseNiveau $maitrise) : MaitriseNiveau
    {
        $this->updateFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param MaitriseNiveau $maitrise
     * @return MaitriseNiveau
     */
    public function historise(MaitriseNiveau $maitrise) : MaitriseNiveau
    {
        $this->historiserFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param MaitriseNiveau $maitrise
     * @return MaitriseNiveau
     */
    public function restore(MaitriseNiveau $maitrise) : MaitriseNiveau
    {
        $this->restoreFromTrait($maitrise);
        return $maitrise;
    }

    /**
     * @param MaitriseNiveau $maitrise
     * @return MaitriseNiveau
     */
    public function delete(MaitriseNiveau $maitrise) : MaitriseNiveau
    {
        $this->deleteFromTrait($maitrise);
        return $maitrise;
    }

    /** QUERY *********************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(MaitriseNiveau::class)->createQueryBuilder('maitrise');
        return $qb;
    }

    /**
     * @param string $type
     * @param string $champ
     * @param string $ordre
     * @param bool $nonHistorise
     * @return MaitriseNiveau[]
     */
    public function getMaitrisesNiveaux(string $type = "", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('maitrise.' . $champ, $ordre);

        if ($type !== null AND $type !== "") {
            $qb = $qb->andWhere('maitrise.type = :type')
                ->setParameter('type', $type);
        }

        if ($nonHistorise !== true) $qb = $qb->andWhere('maitrise.histoDestruction IS NULL');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $type
     * @param string $champ
     * @param string $ordre
     * @param bool $nonHistorise
     * @return array
     */
    public function getMaitrisesNiveauxAsOptions(string $type="", string $champ = 'niveau', string $ordre = 'ASC', bool $nonHistorise = false) : array
    {
        $maitrises = $this->getMaitrisesNiveaux($type, $champ, $ordre, $nonHistorise);
        $options = [];
        foreach ($maitrises as $maitrise) {
            $options[$maitrise->getId()] = $maitrise->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return MaitriseNiveau|null
     */
    public function getMaitriseNiveau(?int $id) : ?MaitriseNiveau
    {
        if ($id === null) return null;
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return MaitriseNiveau|null
     */
    public function getRequestedMaitriseNiveau(AbstractActionController $controller, string $param = 'maitrise') : ?MaitriseNiveau
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getMaitriseNiveau($id);
        return $result;
    }

    /**
     * @param int $niveau
     * @return MaitriseNiveau|null
     */
    public function getMaitriseNiveauByNiveau(int $niveau) : ?MaitriseNiveau
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('maitrise.niveau = :niveau')
            ->setParameter('niveau', $niveau)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs MaitriseNiveau partagent le même niveau [".$niveau."]",0,$e);
        }
        return $result;
    }
}