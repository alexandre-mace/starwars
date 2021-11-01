<?php

namespace App\Command;

use App\Domain\Compute\OddsComputer;
use App\Domain\Factory\EmpireConfigurationFactory;
use App\Domain\Factory\MilleniumFalconConfigurationFactory;
use App\Domain\Model\UniverseDbMock;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GiveMeTheOddsCommand extends Command
{
    private OddsComputer $oddsComputer;
    private EmpireConfigurationFactory $empireConfigurationFactory;
    private MilleniumFalconConfigurationFactory $milleniumFalconConfigurationFactory;

    public function __construct(
        string $name = null,
        OddsComputer $oddsComputer,
        EmpireConfigurationFactory $empireConfigurationFactory,
        MilleniumFalconConfigurationFactory $milleniumFalconConfigurationFactory
    )
    {
        parent::__construct($name);
        $this->oddsComputer = $oddsComputer;
        $this->empireConfigurationFactory = $empireConfigurationFactory;
        $this->milleniumFalconConfigurationFactory = $milleniumFalconConfigurationFactory;
    }

    protected static $defaultName = 'app:give-me-the-odds';

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = $this->oddsComputer->compute(
            $this->milleniumFalconConfigurationFactory->createExample1(),
            $this->empireConfigurationFactory->createExample4(),
            UniverseDbMock::getMock()
        );

        $output->writeln($result);

        return Command::SUCCESS;
    }
}