<?php

namespace App\Command;

use App\Service\ShipXDataProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
	name: 'app:ship-x',
	description: 'Fetch data from ShipX.',
)]
class ShipXCommand extends Command
{
	public function __construct(
		private ShipXDataProvider $provider,
	)
	{
		parent::__construct();
	}

	protected function configure(): void
	{
		$this
			->addArgument('resource', InputArgument::REQUIRED, 'Resource name (e.g. points)')
			->addArgument('city', InputArgument::REQUIRED, 'City name (e.g. Poznan)')
		;
	}

	protected function execute(InputInterface $input, OutputInterface $output): int
	{
		$data =  $this->provider->getData(
			$input->getArgument( 'resource'),
			['city' => $input->getArgument( 'city')]
		);

		$output->write( var_export( $data, true));


		return Command::SUCCESS;
	}
}
