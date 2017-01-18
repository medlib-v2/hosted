# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'json'
require 'yaml'

VAGRANTFILE_API_VERSION ||= "2"
confDir = $confDir ||= File.expand_path("~/.hosted")

hostedYamlPath = confDir + "/Hosted.yaml"
hostedJsonPath = confDir + "/Hosted.json"
afterScriptPath = confDir + "/after.sh"
aliasesPath = confDir + "/aliases"

require File.expand_path(File.dirname(__FILE__) + '/scripts/Hosted.rb')

Vagrant.require_version '>= 1.8.4'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
    if File.exist? aliasesPath then
        config.vm.provision "file", source: aliasesPath, destination: "/tmp/bash_aliases"
        config.vm.provision "shell" do |s|
          s.inline = "awk '{ sub(\"\r$\", \"\"); print }' /tmp/bash_aliases > /home/vagrant/.bash_aliases"
        end
    end

    if File.exist? hostedYamlPath then
        settings = YAML::load(File.read(hostedYamlPath))
    elsif File.exist? hostedJsonPath then
        settings = JSON.parse(File.read(hostedJsonPath))
    end

    Hosted.configure(config, settings)

    if File.exist? afterScriptPath then
        config.vm.provision "shell", path: afterScriptPath, privileged: false
    end

    if defined? VagrantPlugins::HostsUpdater
        config.hostsupdater.aliases = settings['sites'].map { |site| site['map'] }
    end

    if Vagrant.has_plugin? 'vagrant-hostmanager'
        config.hostmanager.enabled = true
        config.hostmanager.manage_host = true
        config.hostmanager = settings['sites'].map { |site| site['map'] }
        config.vm.provision :hostmanager
    end
end
