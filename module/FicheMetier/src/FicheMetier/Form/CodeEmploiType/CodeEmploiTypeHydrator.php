<?php

namespace FicheMetier\Form\CodeEmploiType;

use Carriere\Service\FonctionType\FonctionTypeServiceAwareTrait;
use FicheMetier\Entity\Db\CodeEmploiType;
use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;

class CodeEmploiTypeHydrator implements HydratorInterface
{
    use FonctionTypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var CodeEmploiType $object */

        $data = [
            'code_fonction' => ($object->getCodeFonction())?$object->getCodeFonction()->getId():null,
            'correspondance' => $object->getCorrespondance(),
            'niveau' => $object->getNiveau(),
        ];

        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $fonction = (isset($data['code_fonction'])) ? $this->getFonctionTypeService()->getFonctionType($data['code_fonction']) : null;
        $correspondance = (isset($data['correspondance']) AND trim($data['correspondance']) != '')?trim($data['correspondance']):null;
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) != '')?trim($data['niveau']):null;

        /** @var CodeEmploiType $object */
        $object->setCodeFonction($fonction);
        $object->setCorrespondance($correspondance);
        $object->setNiveau($niveau);
        return $object;
    }


}
