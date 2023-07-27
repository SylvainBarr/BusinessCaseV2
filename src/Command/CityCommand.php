<?php

namespace App\Command;

use App\Entity\City;
use App\Service\HttpClientService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:city',
    description: 'Add in database all frenh cities from the government API',
)]
class CityCommand extends Command
{
    public function __construct(
        private HttpClientService $httpClientService,
        private EntityManagerInterface $em,
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $response = $this->httpClientService->getFrom('https://geo.api.gouv.fr/communes');

        $arrayCommunes = json_decode($response->getContent(), true);

        $io->writeln('Liste des communes récupérée depuis l\'API');

        $cityNumber = 0;
        foreach($arrayCommunes as $arrayCommune){
            if(sizeof($arrayCommune['codesPostaux']) > 0){
                $postalCode = $arrayCommune['codesPostaux'][0];
            }else{
                $postalCode = 0;
            }

            $city = (new City())
                ->setName($arrayCommune['nom'])
                ->setPostalCode($postalCode)
            ;
            $this->em->persist($city);
            $cityNumber ++;

        }

        $this->em->flush();

        $io->writeln($cityNumber.' communes bien ajoutées en BDD');

        return Command::SUCCESS;
    }
}
