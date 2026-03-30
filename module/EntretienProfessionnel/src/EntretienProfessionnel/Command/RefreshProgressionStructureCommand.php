<?php

namespace EntretienProfessionnel\Command;

use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\CampagneProgressionStructure\CampagneProgressionStructureServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenIndicateur\Service\Abonnement\AbonnementServiceAwareTrait;
use UnicaenIndicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;

class RefreshProgressionStructureCommand extends Command
{
    use CampagneServiceAwareTrait;
    use StructureServiceAwareTrait;
    use CampagneProgressionStructureServiceAwareTrait;

    protected static $defaultName = 'entretien-professionnel:refresh-progression';

    protected function configure(): void
    {
        $this->setDescription("Rafraichit les indicateurs de progression des campagnes d'entretien professionnel.");
        $this->addOption('campagne', null,InputArgument::OPTIONAL, "Identifiant de la campagne à rafraichir (si null alors seules les campagnes actives seront rafraichies).");
        $this->addOption('structure', null,InputArgument::OPTIONAL, "Identifiant de la structure à rafraichir (si null alors les structures actives au moment de la campagne seront rafraichies).");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $campagneId = $input->getOption('campagne');
        $structureId = $input->getOption('structure');

        $io->title("Rafraichissement de la progression des campagnes d'entretien professionnel.");

        $campagnes = [];
        if ($campagneId === null) {
            $campagnes = $this->getCampagneService()->getCampagnesActives();
            $message = "Aucune campagne de précisée";
            foreach ($campagnes as $campagne) {
                $message .= "\nCampagne active : " . $campagne->getAnnee(). " #".$campagne->getId();
            }
            $io->info($message);
        } else {
            if ($campagneId === 'all') {
                $message = "Rafraichissement de toutes les campagnes";
                $campagnes = $this->getCampagneService()->getCampagnes();
                foreach ($campagnes as $campagne) {
                    $campagnes[] = $campagne;
                    $message .= "\nCampagne : " . $campagne->getAnnee(). " #".$campagne->getId();
                }
                $io->info($message);

            } else {
                $campagne = $this->getCampagneService()->getCampagne($campagneId);
                if ($campagne === null) {
                    $io->error("Aucune campagne identifiée #" . $campagneId . ".");
                    return Command::FAILURE;
                } else {
                    $message = "Rafraichissement de la campagne " . $campagne->getAnnee() . " #" . $campagne->getId();
                    $io->info($message);
                    $campagnes[] = $campagne;
                }
            }
        }

        if ($structureId === null) {
            //todo amélioration possible récupéré les structures ouvertes lors de la campagnes
            $structures = $this->getStructureService()->getStructures();
            $message = "Aucune structure de précisée (" . count($structures) . " structures récupérées).";
            $io->info($message);
        } else {
            $structure = $this->getStructureService()->getStructure($structureId);
            if ($structure === null) {
                $io->error("Aucune structure identifiée #".$structureId. ".");
                return Command::FAILURE;
            } else {
                $structures = [ $structure ];
                $message = "Rafraichissement pour la structure " . $structure->getLibelleLong(). " #".$structure->getId();
                $io->info($message);
            }
        }

        $nbStructure = count($structures);


        $start = microtime(true);
        foreach ($campagnes as $campagne) {
            $io->text("Traitement de la campagne " .$campagne->getAnnee(). " #".$campagne->getId());
            $position = 1;
            foreach ($structures as $structure) {
                $io->text("Traitement [" . $position . "/" . $nbStructure . "] " . $structure->getLibelleLong() . " #" . $structure->getId());
                $this->getCampagneProgressionStructureService()->refresh($campagne, $structure);
                $position++;
            }
        }
        $end = microtime(true);
        $duration = $end - $start;
        $io->info(sprintf('Commande exécutée en %.2f secondes', $duration));
        $io->success("Terminé");
        return self::SUCCESS;
    }
}