<?php

namespace Medlib\Hosted;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{
    /**
     * The base path of the Medlib installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The name of the project folder.
     *
     * @var string
     */
    protected $projectName;

    /**
     * Sluggified Project Name.
     *
     * @var string
     */
    protected $defaultName;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->basePath = getcwd();
        $this->projectName = basename(getcwd());
        $this->defaultName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->projectName)));

        $this
            ->setName('make')
            ->setDescription('Install Hosted into the current project')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The name of the virtual machine.', $this->defaultName)
            ->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname of the virtual machine.', $this->defaultName)
            ->addOption('ip', null, InputOption::VALUE_OPTIONAL, 'The IP address of the virtual machine.')
            ->addOption('after', null, InputOption::VALUE_NONE, 'Determines if the after.sh file is created.')
            ->addOption('aliases', null, InputOption::VALUE_NONE, 'Determines if the aliases file is created.')
            ->addOption('example', null, InputOption::VALUE_NONE, 'Determines if a Hosted.yaml.example file is created.');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (! file_exists($this->basePath.'/Vagrantfile')) {
            copy(__DIR__.'/stubs/LocalizedVagrantfile', $this->basePath.'/Vagrantfile');
        }

        if (! file_exists($this->basePath.'/Hosted.yaml') && ! file_exists($this->basePath.'/Hosted.yaml.example')) {
            copy(__DIR__.'/stubs/Hosted.yaml', $this->basePath.'/Hosted.yaml');

            if ($input->getOption('name')) {
                $this->updateName($input->getOption('name'));
            }

            if ($input->getOption('hostname')) {
                $this->updateHostName($input->getOption('hostname'));
            }

            if ($input->getOption('ip')) {
                $this->updateIpAddress($input->getOption('ip'));
            }
        } elseif (! file_exists($this->basePath.'/Hosted.yaml')) {
            copy($this->basePath.'/Hosted.yaml.example', $this->basePath.'/Hosted.yaml');

            if ($input->getOption('ip')) {
                $this->updateIpAddress($input->getOption('ip'));
            }
        }

        if ($input->getOption('after')) {
            if (! file_exists($this->basePath.'/after.sh')) {
                copy(__DIR__.'/stubs/after.sh', $this->basePath.'/after.sh');
            }
        }

        if ($input->getOption('aliases')) {
            if (! file_exists($this->basePath.'/aliases')) {
                copy(__DIR__.'/stubs/aliases', $this->basePath.'/aliases');
            }
        }

        if ($input->getOption('example')) {
            if (! file_exists($this->basePath.'/Hosted.yaml.example')) {
                copy($this->basePath.'/Hosted.yaml', $this->basePath.'/Hosted.yaml.example');
            }
        }

        $this->configurePaths();

        $output->writeln('Hosted Installed!');
    }

    /**
     * Update paths in Hosted.yaml.
     *
     * @return void
     */
    protected function configurePaths()
    {
        $yaml = str_replace(
            '- map: ~/sites', '- map: "'.str_replace('\\', '/', $this->basePath).'"', $this->getHostedFile()
        );

        $yaml = str_replace(
            'to: /home/vagrant/sites', 'to: "/home/vagrant/'.$this->defaultName.'"', $yaml
        );

        // Fix path to the public folder (sites: to:)
        $yaml = str_replace(
            $this->defaultName.'"/medlib/public', $this->defaultName.'/public"', $yaml
        );

        file_put_contents($this->basePath.'/Hosted.yaml', $yaml);
    }

    /**
     * Update the "name" variable of the Hosted.yaml file.
     *
     * VirtualBox requires a unique name for each virtual machine.
     *
     * @param  string  $name
     * @return void
     */
    protected function updateName($name)
    {
        file_put_contents($this->basePath.'/Hosted.yaml', str_replace(
            'cpus: 1', 'cpus: 1'.PHP_EOL.'name: '.$name, $this->getHostedFile()
        ));
    }

    /**
     * Set the virtual machine's hostname setting in the Hosted.yaml file.
     *
     * @param  string  $hostname
     * @return void
     */
    protected function updateHostName($hostname)
    {
        file_put_contents($this->basePath.'/Hosted.yaml', str_replace(
            'cpus: 1', 'cpus: 1'.PHP_EOL.'hostname: '.$hostname, $this->getHostedFile()
        ));
    }

    /**
     * Set the virtual machine's IP address setting in the Hosted.yaml file.
     *
     * @param  string  $ip
     * @return void
     */
    protected function updateIpAddress($ip)
    {
        file_put_contents($this->basePath.'/Hosted.yaml', str_replace(
            'ip: "192.168.127.13"', 'ip: "'.$ip.'"', $this->getHostedFile()
        ));
    }

    /**
     * Get the contents of the Hosted.yaml file.
     *
     * @return string
     */
    protected function getHostedFile()
    {
        return file_get_contents($this->basePath.'/Hosted.yaml');
    }
}
