<?php

namespace Tests\Commands;

use Symfony\Component\Yaml\Yaml;
use Medlib\Hosted\MakeCommand;
use Tests\Traits\GeneratesTestDirectory;
use PHPUnit\Framework\TestCase as TestCase;
use Medlib\Hosted\Traits\GeneratesSlugs;
use Symfony\Component\Console\Tester\CommandTester;

class MakeCommandTest extends TestCase
{
    use GeneratesSlugs, GeneratesTestDirectory;

    /** @test */
    public function testDisplaysASuccessMessage()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertContains('Hosted Box Installed!', $tester->getDisplay());
    }

    /** @test */
    public function testReturnsASuccessStatusCode()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(0, $tester->getStatusCode());
    }

    /** @test */
    public function testVagrantFileIsCreatedIfItDoesNotExists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile')
        );
        $this->assertEquals(
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile'),
            file_get_contents(__DIR__.'/../../resources/LocalizedVagrantfile')
        );
    }

    /** @test */
    public function testExistingVagrantFileIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile',
            'Already existing Vagrantfile'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            'Already existing Vagrantfile',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Vagrantfile')
        );
    }

    /** @test */
    public function testAliasesFileIsCreatedIfRequested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--aliases' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases')
        );
        $this->assertEquals(
            file_get_contents(__DIR__.'/../../resources/aliases'),
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases')
        );
    }

    /** @test */
    public function testExistingAliasesFileIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'aliases',
            'Already existing aliases'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--aliases' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases')
        );
        $this->assertEquals(
            'Already existing aliases',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'aliases')
        );
    }

    /** @test */
    public function testAfterShellScriptIsCreatedIfRequested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--after' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
        $this->assertEquals(
            file_get_contents(__DIR__.'/../../resources/after.sh'),
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
    }

    /** @test */
    public function testExistingAfterShellScriptIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh',
            'Already existing after.sh'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--after' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
        $this->assertEquals(
            'Already existing after.sh',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'after.sh')
        );
    }

    /** @test */
    public function testExampleHostedYamlSettingsIsCreatedIfRequested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example')
        );
    }

    /** @test */
    public function testExistingExampleHostedYamlSettingsIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example',
            'name: Already existing Settings.yaml.example'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example')
        );
        $this->assertEquals(
            'name: Already existing Settings.yaml.example',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example')
        );
    }

    /** @test */
    public function testexampleHostedJsonSettingsIsCreatedIfRequested()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example')
        );
    }

    /** @test */
    public function testExistingExampleHostedJsonSettingsIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example',
            '{"name": "Already existing Settings.json.example"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--example' => true,
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example')
        );
        $this->assertEquals(
            '{"name": "Already existing Settings.json.example"}',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example')
        );
    }

    /** @test */
    public function testHostedYamlSettingsIsCreatedIfItIsDoesNotExists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml')
        );
    }

    /** @test */
    public function testExistingHostedYamlSettingsIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml',
            'name: Already existing Settings.yaml'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            'name: Already existing Settings.yaml',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml')
        );
    }

    /** @test */
    public function testHostedJsonSettingsIsCreatedIfItIsRequestedAndItDoesNotExists()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json')
        );
    }

    /** @test */
    public function testExistingHostedJsonSettingsIsNotOverwritten()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json',
            '{"message": "Already existing Settings.json"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertEquals(
            '{"message": "Already existing Settings.json"}',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json')
        );
    }

    /** @test */
    public function testHostedYamlSettingsIsCreatedFromAHostedYamlExampleIfItExists()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example',
            "message: 'Already existing Settings.yaml.example'"
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml')
        );
        $this->assertContains(
            "message: 'Already existing Settings.yaml.example'",
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml')
        );
    }

    /** @test */
    public function testHostedYamlSettings_createdFromAHostedYamlExampleCanOverrideTheIpAddress()
    {
        copy(
            __DIR__ . '/../../resources/Settings.yaml',
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml.example'
        );

        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--ip' => '192.168.127.15',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));

        $this->assertEquals('192.168.127.15', $settings['ip']);
    }

    /** @test */
    public function testHostedJsonSettingsIsCreatedFromAHostedJsonExampleIfIsRequestedAndIfItExists()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example',
            '{"message": "Already existing Settings.json.example"}'
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(
            file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json')
        );
        $this->assertContains(
            '"message": "Already existing Settings.json.example"',
            file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json')
        );
    }

    /** @test */
    public function testHostedJsonSettingsCreatedFromAHostedJsonExampleCanOverrideTheIpAddress()
    {
        copy(
            __DIR__ . '/../../resources/Settings.json',
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json.example'
        );

        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
            '--ip' => '192.168.127.15',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'));
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'), true);

        $this->assertEquals('192.168.127.15', $settings['ip']);
    }

    /** @test */
    public function testHostedYamlSettingsCanBeCreatedWithSomeCommandOptionsOverrides()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--name' => 'test_name',
            '--hostname' => 'test_hostname',
            '--ip' => '127.0.0.1',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $this->assertEquals('test_name', $settings['name']);
        $this->assertEquals('test_hostname', $settings['hostname']);
        $this->assertEquals('127.0.0.1', $settings['ip']);
    }

    /** @test */
    public function testHostedJsonSettingsCanBeCreatedWithSomeCommandOptionsOverrides()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
            '--name' => 'test_name',
            '--hostname' => 'test_hostname',
            '--ip' => '127.0.0.1',
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'));
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'), true);
        $this->assertEquals('test_name', $settings['name']);
        $this->assertEquals('test_hostname', $settings['hostname']);
        $this->assertEquals('127.0.0.1', $settings['ip']);
    }

    /** @test */
    public function testHostedYamlSettingsHasPreConfiguredSites()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $this->assertEquals([
            'map' => "{$projectDirectory}.app",
            'to' => "/home/vagrant/sites/{$projectName}/public",
        ], $settings['sites'][0]);
    }

    /** @test */
    public function testHostedJsonSettingsHasPreConfiguredSites()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'), true);
        $this->assertEquals([
            'map' => "{$projectDirectory}.app",
            'to' => "/home/vagrant/sites/{$projectName}/public",
        ], $settings['sites'][0]);
    }

    /** @test */
    public function testHostedYamlSettingsHasPreConfiguredSharedFolders()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = Yaml::parse(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml'));

        // The "map" is not tested for equality because getcwd() (The method to obtain the project path)
        // returns a directory in a different location that the test directory itself.
        //
        // Example:
        //  - project directory: /private/folders/...
        //  - test directory: /var/folders/...
        //
        // The curious thing is that both directories point to the same location.
        //
        $this->assertRegExp("/{$projectDirectory}/", $settings['folders'][0]['map']);
        $this->assertEquals("/home/vagrant/sites/{$projectName}", $settings['folders'][0]['to']);
    }

    /** @test */
    public function testHostedJsonSettingsHasPreConfiguredSharedFolders()
    {
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([
            '--json' => true,
        ]);

        $this->assertTrue(file_exists(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'));
        $projectDirectory = basename(getcwd());
        $projectName = $this->slug($projectDirectory);
        $settings = json_decode(file_get_contents(self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json'), true);

        // The "map" is not tested for equality because getcwd() (The method to obtain the project path)
        // returns a directory in a different location that the test directory itself.
        //
        // Example:
        //  - project directory: /private/folders/...
        //  - test directory: /var/folders/...
        //
        // The curious thing is that both directories point to the same location.
        //
        $this->assertRegExp("/{$projectDirectory}/", $settings['folders'][0]['map']);
        $this->assertEquals("/home/vagrant/sites/{$projectName}", $settings['folders'][0]['to']);
    }

    /** @test */
    public function testWarningIsThrownIfTheHostedSettingsJsonAndYamlExistsAtTheSameTime()
    {
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json',
            '{"message": "Already existing Settings.json"}'
        );
        file_put_contents(
            self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml',
            "message: 'Already existing Settings.yaml'"
        );
        $tester = new CommandTester(new MakeCommand());

        $tester->execute([]);

        $this->assertContains('WARNING! You have Settings.yaml AND Settings.json configuration files', $tester->getDisplay());
    }
}