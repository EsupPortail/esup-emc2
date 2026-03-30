<?php

namespace EmploiRepere\Service\EmploiRepere;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EmploiRepere\Entity\Db\EmploiRepere;
use FicheMetier\Entity\Db\FicheMetier;
use http\Exception\RuntimeException;
use Laminas\Mvc\Controller\AbstractActionController;

class EmploiRepereService {
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(EmploiRepere $emploiRepere): void
    {
        $this->getObjectManager()->persist($emploiRepere);
        $this->getObjectManager()->flush($emploiRepere);
    }

    public function update(EmploiRepere $emploiRepere): void
    {
        $this->getObjectManager()->flush($emploiRepere);
    }

    public function historise(EmploiRepere $emploiRepere): void
    {
        $emploiRepere->historiser();
        $this->getObjectManager()->flush($emploiRepere);
    }

    public function restore(EmploiRepere $emploiRepere): void
    {
        $emploiRepere->dehistoriser();
        $this->getObjectManager()->flush($emploiRepere);
    }

    public function delete(EmploiRepere $emploiRepere): void
    {
        $this->getObjectManager()->remove($emploiRepere);
        $this->getObjectManager()->flush($emploiRepere);
    }

    /** QUERRY ********************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(EmploiRepere::class)->createQueryBuilder('emploirepere')
            ->leftjoin('emploirepere.codesFonctionsFichesMetiers', 'codeFonctionFicheMetier')->addSelect('codeFonctionFicheMetier')
        ;
        return $qb;
    }

    public function getEmploiRepere(?int $id): ?EmploiRepere
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('emploirepere.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".EmploiRepere::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedEmploiRepere(AbstractActionController $controller, string $param = "emploi-repere"): ?EmploiRepere
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getEmploiRepere($id);
        return $result;
    }

    /** @return EmploiRepere[] */
    public function getEmploisReperes(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto)  $qb = $qb->andWhere('emploirepere.histoDestruction IS NULL');
        $result =  $qb->getQuery()->getResult();
        return $result;
    }

    public function getEmploiRepereByCode(?string $code): ?EmploiRepere
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('emploirepere.code = :code')->setParameter('code', $code);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".EmploiRepere::class."] partagent le même code [".$code."]",0,$e);
        }
        return $result;
    }

    public function getEmploisReperesAsOptions(bool $withHisto = false): array
    {
        $emplois = $this->getEmploisReperes($withHisto);
        $options = [];
        foreach ($emplois as $emploi) {
            $options[$emploi->getId()] = $this->optionify($emploi);
        }
        return $options;
    }

    /** @return EmploiRepere[] */
    public function getEmploiRepereByFicheMetier(FicheMetier $fichemetier): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('codeFonctionFicheMetier.ficheMetier = :ficheMetier')->setParameter('ficheMetier', $fichemetier)
            ->andWhere('emploirepere.histoDestruction IS NULL')
            ->orderBy('emploirepere.libelle', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }


    /** FACADE  *******************************************************************************/

    public function optionify(EmploiRepere $emploiRepere): array
    {
        $value = $emploiRepere->getId();
        $texte = $emploiRepere->getLibelle();
        $option = $emploiRepere->getLibelle(). " " . "<code>".$emploiRepere->getCode()."</code>";
        $this_option = [
            'value' => $value,
            'attributes' => [
                'data-content' => $option
            ],
            'label' => $texte,
        ];
        return $this_option;
    }



}
