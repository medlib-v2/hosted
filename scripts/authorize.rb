# Configure the public key for SSH access
class Authorize
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    return unless settings['authorize']
    return unless File.exist?(File.expand_path(settings['authorize']))

    config.vm.provision :shell do |s|
      s.name   = 'Configure the public key for SSH access'
      s.inline = "echo $1 | grep -xq \"$1\" $2 || echo \"\n$1\" | tee -ia $2"
      s.args   = [
          File.read(File.expand_path(settings['authorize'])),
          '/home/vagrant/.ssh/authorized_keys'
      ]
    end
  end
end