<?php

namespace Application\Form\FicheProfil;

use Application\Entity\Db\FicheProfil;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use DateTime;
use Structure\Service\Structure\StructureServiceAwareTrait;
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
            'vacance_emploi'=> ($object->isVacanceEmploi())?1:0,
            'structure'     => ($object->getStructure())?$object->getStructure()->getLibelleLong():null,
            'structure_id'  => ($object->getStructure())?$object->getStructure()->getId():null,
            'ficheposte'    => ($object->getFichePoste())?$object->getFichePoste()->getId():null,
            'date_audition' => ($object->getDateAudition())?$object->getDateAudition()->format('d/m/Y'):null,
            'date_dossier'  => ($object->getDateDossier())?$object->getDateDossier()->format('d/m/Y'):null,
            'lieu'          => $object->getLieu(),
            'contexte'      => $object->getContexte(),
            'mission'       => $object->getMission(),
            'niveau'        => $object->getNiveau(),
            'contrat'       => $object->getContrat(),
            'renumeration'  => $object->getRenumeration(),
            'adresse'       => $object->getAdresse(),
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
        $vacanceEmploi = (isset($data['vacance_emploi']) AND $data['vacance_emploi'] === '1')?true:false;
        $structure = (isset($data['structure_id']))?$this->getStructureService()->getStructure($data['structure_id']):null;
        $ficheposte = (isset($data['ficheposte']))?$this->getFichePosteService()->getFichePoste($data['ficheposte']):null;
        $dateAudition = (isset($data['date_audition']) AND trim($data['date_audition']) !== '')?DateTime::createFromFormat('d/m/Y', $data['date_audition']):null;
        $dateDossier = (isset($data['date_dossier']) AND trim($data['date_dossier']) !== '')?DateTime::createFromFormat('d/m/Y', $data['date_dossier']):null;
        $adresse = (isset($data['adresse']) AND trim($data['adresse']) !== '')?trim($data['adresse']):null;
        $lieu = (isset($data['lieu']) AND trim($data['lieu']) !== '')?trim($data['lieu']):null;
        $contexte = (isset($data['contexte']) AND trim($data['contexte']) !== '')?trim($data['contexte']):null;
        $mission = (isset($data['mission']) AND trim($data['mission']) !== '')?trim($data['mission']):null;
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) !== '')?trim($data['niveau']):null;
        $contrat = (isset($data['contrat']) AND trim($data['contrat']) !== '')?trim($data['contrat']):null;
        $remuneration = (isset($data['renumeration']) AND trim($data['renumeration']) !== '')?trim($data['renumeration']):null;

        $object->setVancanceEmploi($vacanceEmploi);
        $object->setStructure($structure);
        $object->setFichePoste($ficheposte);
        $object->setDateAudition($dateAudition);
        $object->setDateDossier($dateDossier);
        $object->setLieu($lieu);
        $object->setContexte($contexte);
        $object->setMission($mission);
        $object->setNiveau($niveau);
        $object->setContrat($contrat);
        $object->setRenumeration($remuneration);
        $object->setAdresse($adresse);
        return $object;
    }
}