<?php

namespace Application\Service\Competence;

use Application\Entity\Db\Competence;
use Application\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class CompetenceService {
    use GestionEntiteHistorisationTrait;
    use CompetenceThemeServiceAwareTrait;

    /** COMPETENCES : ENTITY ******************************************************************************************/

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function create(Competence $competence)
    {
        $this->createFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function update(Competence $competence)
    {
       $this->updateFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function historise(Competence $competence)
    {
        $this->updateFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function restore(Competence $competence)
    {
       $this->restoreFromTrait($competence);
        return $competence;
    }

    /**
     * @param Competence $competence
     * @return Competence
     */
    public function delete(Competence $competence)
    {
        $this->deleteFromTrait($competence);
        return $competence;
    }

    /** COMPETENCES : REQUETAGE ***************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilderForCompetence()
    {
        $qb = $this->getEntityManager()->getRepository(Competence::class)->createQueryBuilder('competence')
            ->addSelect('type')->leftJoin('competence.type', 'type')
            ->addSelect('theme')->leftJoin('competence.theme', 'theme')
            ->addSelect('createur')->join('competence.histoCreateur', 'createur')
            ->addSelect('modificateur')->join('competence.histoModificateur', 'modificateur')
            ->addSelect('destructeur')->leftJoin('competence.histoDestructeur', 'destructeur')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return Competence[]
     */
    public function getCompetences($champ = 'libelle', $order = 'ASC')
    {
        $qb = $this->createQueryBuilderForCompetence()
            ->orderBy('competence.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getCompetencesByTypes()
    {
        $competences = $this->getCompetences();

        $array = [];
        foreach ($competences as $competence) {
            $libelle = $competence->getType() ? $competence->getType()->getLibelle() : "Sans type";
            $array[$libelle][] = $competence;
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return Competence
     */
    public function getCompetence(int $id)
    {
        $qb = $this->createQueryBuilderForCompetence()
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

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Competence
     */
    public function getRequestedCompetence(AbstractActionController $controller, $paramName = 'competence')
    {
        $id = $controller->params()->fromRoute($paramName);
        $competence = $this->getCompetence($id);
        return $competence;
    }

    /**
     * @return array
     */
    public function getCompetencesAsGroupOptions()
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

    /**
     * @param Competence $competence
     * @param int $max_length
     * @return array
     */
    private function competenceOptionify(Competence $competence, $max_length=60) {
        $texte = (strlen($competence->getLibelle())>$max_length)?substr($competence->getLibelle(),0,$max_length)." ...":$competence->getLibelle();
        $this_option = [
            'value' =>  $competence->getId(),
            'attributes' => [
                'data-content' => ($competence->getType())?"<span class='badge ".$competence->getType()->getLibelle()."'>".$competence->getType()->getLibelle()."</span> &nbsp;". $texte:"",
            ],
            'label' => $texte,
        ];
        return $this_option;
    }
}