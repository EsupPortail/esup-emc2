<?php

namespace Application\Form\FicheProfil;

use Application\Entity\Db\FicheProfil;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Zend\Hydrator\HydratorInterface;

class FicheProfilHydrator implements HydratorInterface {
    use FichePosteServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @param FicheProfil $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'structure'     => ($object->getStructure())?$object->getStructure()->getLibelleLong():null,
            'structure_id'     => ($object->getStructure())?$object->getStructure()->getId():null,
            'ficheposte'    => ($object->getFichePoste())?$object->getFichePoste()->getId():null,
            'date'          => ($object->getDate())?$object->getDate()->format('d/m/Y'):null,
            'lieu'          => $object->getLieu(),
            'contexte'      => $object->getContexte(),
            'mission'       => $object->getMission(),
            'niveau'        => $object->getNiveau(),
            'contraintes'   => $object->getContraintes(),
            'contrat'       => $object->getContrat(),
            'remuneration'  => $object->getRenumeration(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheProfil $object
     * @return FicheProfil
     */
    public function hydrate(array $data, $object)
    {
        $structure = (isset($data['structure_id']))?$this->getStructureService()->getStructure($data['structure_id']):null;
        $ficheposte = (isset($data['ficheposte']))?$this->getFichePosteService()->getFichePoste($data['ficheposte']):null;
        $date = (isset($data['date']))?DateTime::createFromFormat('d/m/Y', $data['date']):null;
        $lieu = (isset($data['lieu']) AND trim($data['lieu']) !== '')?trim($data['lieu']):null;
        $contexte = (isset($data['contexte']) AND trim($data['contexte']) !== '')?trim($data['contexte']):null;
        $mission = (isset($data['mission']) AND trim($data['mission']) !== '')?trim($data['mission']):null;
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) !== '')?trim($data['niveau']):null;
        $contraintes = (isset($data['contraintes']) AND trim($data['contraintes']) !== '')?trim($data['contraintes']):null;
        $contrat = (isset($data['contrat']) AND trim($data['contrat']) !== '')?trim($data['contrat']):null;
        $remuneration = (isset($data['remuneration']) AND trim($data['remuneration']) !== '')?trim($data['remuneration']):null;

        $object->setStructure($structure);
        $object->setFichePoste($ficheposte);
        $object->setDate($date);
        $object->setLieu($lieu);
        $object->setContexte($contexte);
        $object->setMission($mission);
        $object->setNiveau($niveau);
        $object->setContraintes($contraintes);
        $object->setContrat($contrat);
        $object->setRenumeration($remuneration);
        return $object;
    }
}