<?php

namespace Tests\Settings;

use Tests\Traits\GeneratesTestDirectory;
use Medlib\Hosted\Settings\JsonSettings;
use PHPUnit\Framework\TestCase as TestCase;


class JsonSettingsTest extends TestCase
{
    use GeneratesTestDirectory;

    /** @test */
    public function testCanBeCreatedFromAFilename()
    {
        $settings = JsonSettings::fromFile(__DIR__ . '/../../resources/Settings.json');
        $attributes = $settings->toArray();
        $this->assertEquals('192.168.127.13', $attributes['ip']);
        $this->assertEquals('2048', $attributes['memory']);
        $this->assertEquals(1, $attributes['cpus']);
    }

    /** @test */
    public function testCanBeSavedToAFile()
    {
        $settings = new JsonSettings([
            'ip' => '192.168.127.13',
            'memory' => '2048',
            'cpus' => 1,
        ]);
        $filename = self::$testDirectory.DIRECTORY_SEPARATOR.'Settings.json';
        $settings->save($filename);
        $this->assertTrue(file_exists($filename));
        $attributes = json_decode(file_get_contents($filename), true);
        $this->assertEquals('192.168.127.13', $attributes['ip']);
        $this->assertEquals('2048', $attributes['memory']);
        $this->assertEquals(1, $attributes['cpus']);
    }

    /** @test */
    public function testCanUpdateItsAttributes()
    {
        $settings = new JsonSettings([
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
    public function itUpdatesOnlyNotNullAttributes()
    {
        $settings = new JsonSettings([
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
        $settings = new JsonSettings(['name' => 'Initial name']);
        $settings->updateName('Updated name');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated name', $attributes['name']);
    }

    /** @test */
    public function testCanUpdateItsHostname()
    {
        $settings = new JsonSettings(['name' => 'Initial ip address']);
        $settings->updateHostname('Updated hostname');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated hostname', $attributes['hostname']);
    }

    /** @test */
    public function testCanUpdateItsIpAddress()
    {
        $settings = new JsonSettings(['name' => 'Initial ip address']);
        $settings->updateIpAddress('Updated ip address');
        $attributes = $settings->toArray();
        $this->assertEquals('Updated ip address', $attributes['ip']);
    }

    /** @test */
    public function testCanConfigureItsSites()
    {
        $settings = new JsonSettings([
            'sites' => [
                [
                    'map' => 'homestead.app',
                    'to' => '/home/vagrant/sites/Laravel/public',
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
        $settings = new JsonSettings([
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