<?php

namespace Tests\Settings;

use Symfony\Component\Yaml\Yaml;
use Tests\Traits\GeneratesTestDirectory;
use PHPUnit\Framework\TestCase as TestCase;
use Medlib\Hosted\Settings\YamlSettings;

class YamlSettingsTest extends TestCase
{
    use GeneratesTestDirectory;
    /** @test */
    public function testCanBeCreatedFromAFilename()
    {
        $settings = YamlSettings::fromFile(__DIR__ . '/../../resources/Settings.yaml');
        $attributes = $settings->toArray();
        $this->assertEquals('192.168.127.13', $attributes['ip']);
        $this->assertEquals('2048', $attributes['memory']);
        $this->assertEquals(1, $attributes['cpus']);
    }

    /** @test */
    public function testCanBeSavedToAFile()
    {
        $settings = new YamlSettings([
            'ip' => '192.168.127.13',
            'memory' => '2048',
            'cpus' => 1,
        ]);
        $filename = self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.yaml';
        $settings->save($filename);
        $this->assertTrue(file_exists($filename));
        $attributes = Yaml::parse(file_get_contents($filename));
        $this->assertEquals('192.168.127.13', $attributes['ip']);
        $this->assertEquals('2048', $attributes['memory']);
        $this->assertEquals(1, $attributes['cpus']);
    }

    /** @test */
    public function testCanUpdateItsAttributes()
    {
        $settings = new YamlSettings([
            'ip' => '192.168.127.13',
            'memory' => '2048',
            'cpus' => 1,
        ]);
        $settings->update([
            'ip' => '127.0.0.1',
            'memory' => '4096',
            'cpus' => 2,
        ]);
        $attributes = $settings->toArray();
        $this->assertEquals('127.0.0.1', $attributes['ip']);
        $this->assertEquals('4096', $attributes['memory']);
        $this->assertEquals(2, $attributes['cpus']);
    }

    /** @test */
    public function testUpdatesOnlyNotNullAttributes()
    {
        $settings = new YamlSettings([
            'ip' => '192.168.127.13',
            'memory' => '2048',
            'cpus' => 1,
        ]);
        $settings->update([
            'ip' => null,
            'memory' => null,
            'cpus' => null,
        ]);
        $attributes = $settings->toArray();
        $this->assertEquals('192.168.127.13', $attributes['ip']);
        $this->assertEquals('2048', $attributes['memory']);
        $this->assertEquals(1, $attributes['cpus']);
    }

    /** @test */
    public function testCanUpdateItsName()
    {
        $settings = new YamlSettings(['name' => 'Initial name']);
        $settings->updateName('Updated name');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated name', $attributes['name']);
    }

    /** @test */
    public function testCanUpdateItsHostname()
    {
        $settings = new YamlSettings(['name' => 'Initial ip address']);
        $settings->updateHostname('Updated hostname');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated hostname', $attributes['hostname']);
    }

    /** @test */
    public function testCan_update_its_ip_address()
    {
        $settings = new YamlSettings(['name' => 'Initial ip address']);
        $settings->updateIpAddress('Updated ip address');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated ip address', $attributes['ip']);
    }

    /** @test */
    public function testCanConfigureItsSites()
    {
        $settings = new YamlSettings([
            'sites' => [
                [
                    'map' => 'medlib.app',
                    'to' => '/home/vagrant/sites/medlib/public',
                    'type' => 'laravel',
                    'schedule' => true,
                ],
            ],
        ]);
        $settings->configureSites('test.com', 'test-com');
        $attributes = $settings->toArray();
        $this->assertEquals([
            'map' => 'test.com.app',
            'to' => '/home/vagrant/sites/test-com/public',
            'type' => 'laravel',
            'schedule' => true,
        ], $attributes['sites'][0]);
    }

    /** @test */
    public function testCanConfigureItsSharedFolders()
    {
        $settings = new YamlSettings([
            'folders' => [
                'map' => '~/sites',
                'to' => '/home/vagrant/sites',
            ],
        ]);
        $settings->configureSharedFolders('/a/path/for/project_name', 'project_name');
        $attributes = $settings->toArray();
        $this->assertEquals([
            'map' => '/a/path/for/project_name',
            'to' => '/home/vagrant/sites/project_name',
        ], $attributes['folders'][0]);
    }
}