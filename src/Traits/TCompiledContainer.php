<?php

namespace Testbench;

use Kdyby\Doctrine\EntityManager;

trait TCompiledContainer
{

	/** @return \Nette\DI\Container */
	protected function getContainer()
	{
		return \Testbench\ContainerFactory::create(FALSE);
	}

	protected function getService($class)
	{
		return $this->getContainer()->getByType($class);
	}

	protected function refreshContainer($config = [])
	{
		$container = \Testbench\ContainerFactory::create(TRUE, $config);

		if ($this instanceof TransactionalTestCase) {
			/** @var EntityManager $em */
			$em = $this->getService(EntityManager::class);
			$em->getConnection()->beginTransaction();
		}

		return $container;
	}

	protected function changeRunLevel($testSpeed = \Testbench::FINE)
	{
		if ((int)getenv('RUNLEVEL') < $testSpeed) {
			\Tester\Environment::skip(
				"Required runlevel '$testSpeed' but current runlevel is '" . (int)getenv('RUNLEVEL') . "' (higher runlevel means slower tests)\n" .
				"You can run this test with environment variable: 'RUNLEVEL=$testSpeed vendor/bin/run-tests ...'\n"
			);
		}
	}

	protected function markTestAsSlow($really = TRUE)
	{
		$this->changeRunLevel($really ? \Testbench::FINE : \Testbench::QUICK);
	}

	protected function markTestAsVerySlow($really = TRUE)
	{
		$this->changeRunLevel($really ? \Testbench::SLOW : \Testbench::QUICK);
	}

}
