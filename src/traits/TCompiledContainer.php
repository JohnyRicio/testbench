<?php

namespace Testbench;

trait TCompiledContainer
{

	/** @return \Nette\DI\Container */
	private function getContainer()
	{
		return \Testbench\ContainerFactory::create(FALSE);
	}

	private function getService($class)
	{
		$container = \Testbench\ContainerFactory::create(FALSE);
		return $container->getByType($class);
	}

	private function refreshContainer()
	{
		return \Testbench\ContainerFactory::create(TRUE);
	}

	private function changeRunLevel($testSpeed = \Testbench::FINE)
	{
		if ((int)getenv('RUNLEVEL') < $testSpeed) {
			\Tester\Environment::skip(
				"Required runlevel '$testSpeed' but current runlevel is '" . (int)getenv('RUNLEVEL') . "' (higher runlevel means slower tests)\n" .
				"You can run this test with environment variable: 'RUNLEVEL=$testSpeed vendor/bin/run-tests ...'\n"
			);
		}
	}

}
