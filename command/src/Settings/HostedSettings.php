<?php

namespace Medlib\Hosted\Settings;

abstract class HostedSettings
{
    /**
     * Settings attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * JsonSettings constructor.
     *
     * @param  array  $attributes
     */
    public function __construct($attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Create an instance from a file.
     *
     * @param  string  $filename
     * @return static
     */
    abstract public static function fromFile($filename);
    /**
     * Save the hosted settings.
     *
     * @param  string  $filename
     * @return void
     */
    abstract public function save($filename);

    /**
     * Update the hosted settings.
     *
     * @param  array  $attributes
     * @return static
     */
    public function update($attributes)
    {
        $this->attributes = array_merge($this->attributes, array_filter($attributes, function ($attribute) {
            return ! is_null($attribute);
        }));
        return $this;
    }

    /**
     * Update the virtual machine's name.
     *
     * @param  string  $name
     * @return static
     */
    public function updateName($name)
    {
        $this->update(['name' => $name]);
        return $this;
    }

    /**
     * Update the virtual machine's hostname.
     *
     * @param  string  $hostname
     * @return static
     */
    public function updateHostname($hostname)
    {
        $this->update(['hostname' => $hostname]);
        return $this;
    }

    /**
     * Update the virtual machine's IP address.
     *
     * @param  string  $ip
     * @return static
     */
    public function updateIpAddress($ip)
    {
        $this->update(['ip' => $ip]);
        return $this;
    }

    /**
     * Configure the nginx sites.
     *
     * @param  string  $projectName
     * @param  string  $projectDirectory
     * @return static
     */
    public function configureSites($projectName, $projectDirectory)
    {
        $site = [
            'map' => "{$projectName}.app",
            'to' => "/home/vagrant/sites/{$projectDirectory}/public",
        ];
        if (isset($this->attributes['sites']) && ! empty($this->attributes['sites'])) {
            if (isset($this->attributes['sites'][0]['type'])) {
                $site['type'] = $this->attributes['sites'][0]['type'];
            }
            if (isset($this->attributes['sites'][0]['schedule'])) {
                $site['schedule'] = $this->attributes['sites'][0]['schedule'];
            }
        }
        $this->update(['sites' => [$site]]);
        return $this;
    }

    /**
     * Configure the shared folders.
     *
     * @param  string  $projectPath
     * @param  string  $projectDirectory
     * @return static
     */
    public function configureSharedFolders($projectPath, $projectDirectory)
    {
        $folder = [
            'map' => $projectPath,
            'to' => "/home/vagrant/sites/{$projectDirectory}",
        ];
        $this->update(['folders' => [$folder]]);
        return $this;
    }

    /**
     * Convert the hosted settings to an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }
}