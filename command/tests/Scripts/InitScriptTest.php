<?php

namespace Tests\Scripts;

use Tests\Traits\GeneratesTestDirectory;
use PHPUnit\Framework\TestCase as TestCase;

class InitScriptTest extends TestCase
{
    use GeneratesTestDirectory;

    /**
     * Copies init.sh and resources directory to the temporal directory.
     */
    public function setUp()
    {
        $projectDirectory = __DIR__.'/../../..';

        exec("cp {$projectDirectory}/init.sh ".self::$testDirectory);
        exec("cp -r {$projectDirectory}/command/resources ".self::$testDirectory);
    }

    /** @test */
    public function testDisplaysASuccessMessage()
    {
        $output = exec('bash init.sh');
        $this->assertEquals('Hosted Box initialized!', $output);
    }

    /** @test */
    public function testCreatesAHostedYamlFile()
    {
        exec('bash init.sh');
        $this->assertTrue(file_exists(self::$testDirectory.'/Settings.yaml'));
    }

    /** @test */
    public function testCreatesAHostedJsonFileIfRequested()
    {
        exec('bash init.sh json');
        $this->assertTrue(file_exists(self::$testDirectory.'/Settings.json'));
    }

    /** @test */
    public function testCreatesAnAfterShellScript()
    {
        exec('bash init.sh');
        $this->assertTrue(file_exists(self::$testDirectory.'/after.sh'));
    }

    /** @test */
    public function testCreatesAnAliasesFile()
    {
        exec('bash init.sh');
        $this->assertTrue(file_exists(self::$testDirectory.'/aliases'));
    }
}