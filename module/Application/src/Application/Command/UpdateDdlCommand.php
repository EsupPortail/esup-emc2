<?php

namespace Application\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Unicaen\BddAdmin\Bdd;

class UpdateDdlCommand extends CommandAbstract
{
    protected static $defaultName = 'update-ddl';



    protected function configure()
    {
        $this
            ->setDescription("Mise à jour de la ddl selon la base de données");
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getIO($input, $output);

        $io->title("Update de la ddl");
        $io->text("Mise à jour de la ddl en cours");

        /**
         *
         * @var $bdd Bdd
         */
        $bdd = $this->getServicemanager()->get(Bdd::class);
        if ($bdd != null) {
            $ddl = $bdd->getDdl();
            try {
                $ddl->saveToDir();
            } catch (\Exception $e) {
                $io->error($e->getMessage());
            }

            $io->success("Ddl mise à jour");
        }

        return self::SUCCESS;
    }
}