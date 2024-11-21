<?php

namespace Application\Command;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\Bdd;

class UpdateDdlCommand extends Command
{
    protected static $defaultName = 'update-ddl';


    protected ?Bdd $bdd = null;

    public function setBdd(?Bdd $bdd): void
    {
        $this->bdd = $bdd;
    }

    protected function configure(): void
    {
        $this->setDescription("Mise à jour de la ddl selon la base de données");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        $io->title("Update de la ddl");
        $io->text("Mise à jour de la ddl en cours");

        /**
         *
         * @var $bdd Bdd
         */
        $bdd = $this->bdd;
        if ($bdd != null) {
            $ddl = $bdd->getDdl();
            try {
                $ddl->saveToDir();
            } catch (Exception $e) {
                $io->error($e->getMessage());
            }

            $io->success("Ddl mise à jour");
        }

        return self::SUCCESS;
    }
}