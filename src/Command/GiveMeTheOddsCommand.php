<?php

namespace App\Command;

use App\Database\UniverseDatabaseLoader;
use App\Domain\Compute\OddsComputer;
use App\Domain\Factory\EmpireConfigurationFactory;
use App\Domain\Factory\MilleniumFalconConfigurationFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class GiveMeTheOddsCommand extends Command
{
    private OddsComputer $oddsComputer;
    private EmpireConfigurationFactory $empireConfigurationFactory;
    private MilleniumFalconConfigurationFactory $milleniumFalconConfigurationFactory;
    private DecoderInterface $decoder;
    private UniverseDatabaseLoader $universeDatabaseLoader;
    private $projectDir;

    public function __construct(
        string $name = null,
        OddsComputer $oddsComputer,
        EmpireConfigurationFactory $empireConfigurationFactory,
        MilleniumFalconConfigurationFactory $milleniumFalconConfigurationFactory,
        DecoderInterface $decoder,
        UniverseDatabaseLoader $universeDatabaseLoader,
        string $projectDir
    )
    {
        parent::__construct($name);
        $this->oddsComputer = $oddsComputer;
        $this->empireConfigurationFactory = $empireConfigurationFactory;
        $this->milleniumFalconConfigurationFactory = $milleniumFalconConfigurationFactory;
        $this->decoder = $decoder;
        $this->universeDatabaseLoader = $universeDatabaseLoader;
        $this->projectDir = $projectDir;
    }

    protected static $defaultName = 'app:give-me-the-odds';

    protected function configure(): void
    {
        $this
            ->addArgument('millenium-falcon', InputArgument::REQUIRED, 'Path towards millenium falcon configuration')
            ->addArgument('empire', InputArgument::REQUIRED, 'Path towards empire configuration')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $decodedMilleniumFalconConfiguration = $this->decodeArgumentConfiguration('millenium-falcon', $input);
        $decodedEmpireConfiguration = $this->decodeArgumentConfiguration('empire', $input);

        $milleniumFalconConfiguration = $this->milleniumFalconConfigurationFactory->create($decodedMilleniumFalconConfiguration);
        $empireConfiguration = $this->empireConfigurationFactory->create($decodedEmpireConfiguration);

        $universeRoutes = $this->universeDatabaseLoader->load($milleniumFalconConfiguration);

        $result = $this->oddsComputer->compute(
            $milleniumFalconConfiguration,
            $empireConfiguration,
            $universeRoutes
        );

        $output->writeln($result);

        return Command::SUCCESS;
    }

    private function decodeArgumentConfiguration($argumentName, InputInterface $input)
    {
        $configurationPath = $this->projectDir . '/public/' . $input->getArgument($argumentName);
        $configuration = file_get_contents($configurationPath);
        return $this->decoder->decode($configuration, 'json');
    }
}