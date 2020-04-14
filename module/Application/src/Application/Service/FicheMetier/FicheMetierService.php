<?php

namespace Application\Service\FicheMetier;

use Application\Entity\Db\Domaine;
use Application\Entity\Db\FamilleProfessionnelle;
use Application\Entity\Db\FicheMetier;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractController;

class FicheMetierService {
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function create($fiche)
    {
        $this->createFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function update($fiche)
    {
        $this->updateFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function historise($fiche)
    {
        $this->historiserFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function restore($fiche)
    {
        $this->restoreFromTrait($fiche);
        return $fiche;
    }

    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function delete($fiche)
    {
        $this->deleteFromTrait($fiche);
        return $fiche;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @param string $order an attribute use to sort
     * @return FicheMetier[]
     */
    public function getFichesMetiers($order = 'id')
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->addSelect('metier')->join('ficheMetier.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
//            ->addSelect('application')->leftJoin('ficheMetier.applications', 'application')
//            ->addSelect('formation')->leftJoin('ficheMetier.formations', 'formation')
//            ->addSelect('competence')->leftJoin('ficheMetier.competences', 'competence')
            ->orderBy('ficheMetier.', $order)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int $id
     * @return FicheMetier
     */
    public function getFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('ficheMetier')
            ->andWhere('ficheMetier.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs fiches métiers portent le même identifiant [".$id."].");
        }
        return $result;
    }

    /**
     * @param AbstractController $controller
     * @param string $name
     * @param bool $notNull
     * @return FicheMetier
     */
    public function getRequestedFicheMetier($controller, $name = 'fiche', $notNull = false)
    {
        $ficheId = $controller->params()->fromRoute($name);
        $fiche = $this->getFicheMetier($ficheId);
        if($notNull && !$fiche) throw new RuntimeException("Aucune fiche de trouvée avec l'identifiant [".$ficheId."]");

        return $fiche;
    }

    /**
     * @return FicheMetier
     */
    public function getLastFicheMetier()
    {
        $fiches = $this->getFichesMetiers('id');
        return end($fiches);
    }

    /**
     * @param FamilleProfessionnelle $famille
     * @return FicheMetier[]
     */
    public function getFicheByFamille($famille)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('fiche')
            ->addSelect('metier')->join('fiche.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->andWhere('famille = :famille')
            ->setParameter('famille', $famille)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Domaine $domaine
     * @return FicheMetier[]
     */
    public function getFicheByDomaine($domaine)
    {
        $qb = $this->getEntityManager()->getRepository(FicheMetier::class)->createQueryBuilder('fiche')
            ->addSelect('metier')->join('fiche.metier', 'metier')
            ->addSelect('domaine')->join('metier.domaine', 'domaine')
            ->addSelect('famille')->join('domaine.famille', 'famille')
            ->andWhere('domaine = :domaine')
            ->setParameter('domaine', $domaine)
            ->orderBy('metier.libelle')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getFichesMetiersAsOptions()
    {
        $fiches = $this->getFichesMetiers();
        $array = [];
        foreach ($fiches as $fiche) {
            $array[$fiche->getId()] = $fiche->getMetier()->getLibelle();
        }
        return $array;
    }

}