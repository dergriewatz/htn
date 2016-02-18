<?php

namespace Tests\AppBundle;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Yaml\Parser;

abstract class IntegrationWebTestCase extends WebTestCase
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var Application
     */
    private $consoleApplication;

    /**
     * @var bool
     */
    private static $dbCreated = false;

    /**
     * @var null
     */
    private static $xdebugInitialStarted = null;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        self::$xdebugInitialStarted = array_filter(
            $_SERVER['argv'],
            function ($a) {
                return strpos($a, 'coverage');
            }
        );

        if (self::$xdebugInitialStarted) {
            xdebug_stop_code_coverage(false);
        }

        $this->em->getConnection()->exec('SET foreign_key_checks=0;');
        if (!self::$dbCreated) {
            $this->resetDb();
            self::$dbCreated = true;
        } else {
            $this->purgeDb();
        }
        $this->em->getConnection()->exec('SET foreign_key_checks=1;');

        if (self::$xdebugInitialStarted) {
            xdebug_start_code_coverage(XDEBUG_CC_UNUSED | XDEBUG_CC_DEAD_CODE);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
    }

    /**
     * @param string $serviceId
     * @return object
     */
    protected function getService($serviceId)
    {
        return $this->getContainer()->get($serviceId);
    }

    /**
     * @param string $commandName
     * @param array $parameters
     * @throws \RuntimeException
     * @return array
     */
    private function runCommand($commandName, array $parameters = [])
    {
        $baseParameters = [
            '--env' => 'test',
            '--quiet' => null,
            'command' => $commandName,
        ];

        $this->getConsoleApplication()->run(
            new ArrayInput(array_merge($baseParameters, $parameters)),
            new NullOutput()
        );
    }

    /**
     * @return Application
     */
    private function getConsoleApplication()
    {
        if (null === $this->consoleApplication) {
            $app = new Application(static::$kernel);
            $app->setAutoExit(false);
            $app->setCatchExceptions(false);
            $this->consoleApplication = $app;
        }

        return $this->consoleApplication;
    }

    /**
     * @return ContainerInterface
     */
    private function getContainer()
    {
        return static::$kernel->getContainer();
    }

    private function resetDb()
    {
        $this->runCommand('doctrine:database:drop', ['--force' => true]);
        $this->runCommand('doctrine:database:create');
        $this->runCommand('doctrine:migration:migrate', ['--no-interaction' => true]);
        //$this->runCommand('doctrine:fixtures:load', ['--purge-with-truncate' => true]);
    }

    private function purgeDb()
    {
        $purger = new ORMPurger($this->em);
        $executor = new ORMExecutor($this->em, $purger);

        $executor->execute([], false);
    }
}
