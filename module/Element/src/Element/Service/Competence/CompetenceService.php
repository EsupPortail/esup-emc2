<?php

namespace Element\Service\Competence;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\Competence;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CompetenceService {
    use EntityManagerAwareTrait;
    use CompetenceThemeServiceAwareTrait;

    /** COMPETENCES : ENTITY ******************************************************************************************/

    public function create(Competence $competence) : Competence
    {
        try {
            $this->getEntityManager()->persist($competence);
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    public function update(Competence $competence) : Competence
    {
        try {
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    public function historise(Competence $competence) : Competence
    {
        try {
            $competence->historiser();
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    public function restore(Competence $competence) : Competence
    {
        try {
            $competence->dehistoriser();
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    public function delete(Competence $competence) : Competence
    {
        try {
            $this->getEntityManager()->remove($competence);
            $this->getEntityManager()->flush($competence);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competence;
    }

    /** COMPETENCES : REQUETAGE ***************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
        ;
        return $qb;
    }

    /** Competence[] */
    public function getCompetences(string $champ = 'libelle', string $order = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getCompetencesByTypes() : array
    {
        $competences = $this->getCompetences();

        $array = [];
        foreach ($competences as $competence) {
            $libelle = $competence->getType() ? $competence->getType()->getLibelle() : "Sans type";
            $array[$libelle][] = $competence;
        }
        return $array;
    }

    public function getCompetence(?int $id) : ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(ORMException $e) {
            throw new RuntimeException("Plusieurs Competence partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCompetence(AbstractActionController $controller, string $paramName = 'competence') : ?Competence
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

    public function getCompetencesAsGroupOptions() : array
    {
        $competences = $this->getCompetences();
        $dictionnaire = [];
        foreach ($competences as $competence) {
            $libelle = ($competence->getTheme()) ? $competence->getTheme()->getLibelle() : "Sans Thèmes";
            $dictionnaire[$libelle][] = $competence;
        }
        ksort($dictionnaire);

        $options = [];
        foreach ($dictionnaire as $clef => $listing) {
            $optionsoptions = [];
            usort($listing, function (Competence $a, Competence $b) { return $a->getLibelle() > $b->getLibelle();});

            foreach ($listing as $competence) {
                $optionsoptions[$competence->getId()] = $this->competenceOptionify($competence);
            }

            $options[] = [
                'label' => $clef,
                'options' => $optionsoptions,
            ];
        }
        return $options;
    }

    private function competenceOptionify(Competence $competence, int $max_length=60) : array
    {
        $texte = (strlen($competence->getLibelle())>$max_length)?substr($competence->getLibelle(),0,$max_length)." ...":$competence->getLibelle();
        $this_option = [
            'value' =>  $competence->getId(),
            'attributes' => [
                'data-content' => ($competence->getType())?"<span class='badge ".$competence->getType()->getLibelle()."'>".$competence->getType()->getLibelle()."</span> &nbsp;". $texte . " " . (($competence->getSource())?"<span class='label' style='background: darkslategrey;'>" . $competence->getSource() . " - " . $competence->getIdSource(). "</span>":""):"",
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    public function getCompetenceByIdSource(string $source, string $id) : ?Competence
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.source = :source')
            ->setParameter('source', $source)
            ->andWhere('competence.idSource = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs compétences partagent le même id source [".$source."-".$id."]");
        }
        return $result;
    }
}