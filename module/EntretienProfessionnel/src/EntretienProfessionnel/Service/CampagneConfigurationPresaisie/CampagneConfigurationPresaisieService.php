<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationPresaisie;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use EntretienProfessionnel\Entity\Db\CampagneConfigurationPresaisie;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;
use UnicaenAutoform\Entity\Db\Formulaire;
use UnicaenAutoform\Service\Formulaire\FormulaireReponseServiceAwareTrait;
use UnicaenRenderer\Service\Macro\MacroServiceAwareTrait;

class CampagneConfigurationPresaisieService {
    use ProvidesObjectManager;

    use FormulaireReponseServiceAwareTrait;
    use MacroServiceAwareTrait;

    /** Gestion des entités *******************************************************************************************/

    public function create(CampagneConfigurationPresaisie $presaisie): void
    {
        $this->getObjectManager()->persist($presaisie);
        $this->getObjectManager()->flush($presaisie);
    }

    public function update(CampagneConfigurationPresaisie $presaisie): void
    {
        $this->getObjectManager()->flush($presaisie);
    }

    public function delete(CampagneConfigurationPresaisie $presaisie): void
    {
        $this->getObjectManager()->remove($presaisie);
        $this->getObjectManager()->flush($presaisie);
    }

    public function historise(CampagneConfigurationPresaisie $presaisie): void
    {
        $presaisie->historiser();
        $this->getObjectManager()->flush($presaisie);
    }

    public function restore(CampagneConfigurationPresaisie $presaisie): void
    {
        $presaisie->dehistoriser();
        $this->getObjectManager()->flush($presaisie);
    }

    /** Requêtage *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(CampagneConfigurationPresaisie::class)->createQueryBuilder('presaisie')
            ->join('presaisie.champ', 'champ')->addSelect('champ')
            ->join('presaisie.macro', 'macro')->addSelect('macro')
        ;
        return $qb;
    }

    public function getCampagneConfigurationPresaisie(?int $id): ?CampagneConfigurationPresaisie
    {
        $qb = $this->createQueryBuilder()
            ->where('presaisie.id = :id')->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".CampagneConfigurationPresaisie::class."] partagent le même id [".$id."]", 0, $e);
        }
        return $result;
    }

    public function getRequestedCampagneConfigurationPresaisie(AbstractActionController $controller, string $param='campagne-configuration-presaisie'): ?CampagneConfigurationPresaisie
    {
        $id = $controller->params()->fromRoute($param);
        $presaisie = $this->getCampagneConfigurationPresaisie($id);
        return $presaisie;
    }

    /** @return CampagneConfigurationPresaisie[] */
    public function getCampagneConfigurationPresaisies(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb->andWhere('presaisie.histoDestruction IS NULL');
        }
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return CampagneConfigurationPresaisie[] */
    public function getCampagneConfigurationPresaisiesByFormulaire(Formulaire $formulaire): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('presaisie.formulaire = :formulaire')->setParameter('formulaire', $formulaire)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** Façade ********************************************************************************************************/

    public function applyPresaisies(EntretienProfessionnel $entretien, ?string $formulaire = null): void
    {
        $vars = [];
        $vars['entretien'] = $entretien;
        $vars['campagne'] = $entretien?->getCampagne();
        $vars['agent'] = $entretien?->getAgent();

        if ($formulaire === null OR $formulaire === 'CREP') {
            $instance = $entretien->getFormulaireInstance();
            $formulaire = $instance->getFormulaire();
            $presaisies = $this->getCampagneConfigurationPresaisiesByFormulaire($instance->getFormulaire());

            foreach ($presaisies as $presaisie) {
                $value = $this->getMacroService()->evaluateMacro($presaisie->getMacro(), $vars);
                $this->getFormulaireReponseService()->setFormulaireReponse($instance, $presaisie->getChamp()->getId(), $value);
            }
        }

        if ($formulaire === null OR $formulaire === 'CREF') {
            $instance = $entretien->getFormationInstance();
            $formulaire = $instance->getFormulaire();
            $presaisies = $this->getCampagneConfigurationPresaisiesByFormulaire($instance->getFormulaire());

            foreach ($presaisies as $presaisie) {
                $value = $this->getMacroService()->evaluateMacro($presaisie->getMacro(), $vars);
                $this->getFormulaireReponseService()->setFormulaireReponse($instance, $presaisie->getChamp()->getId(), $value);
            }
        }
    }
}
