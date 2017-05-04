# Configure Virtualbox Guest Additions
class Vbguest
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    return unless Vagrant.has_plugin? 'vagrant-vbguest'

    config.vbguest.no_install  = true
    config.vbguest.auto_update = false
    config.vbguest.no_remote   = true
  end
end