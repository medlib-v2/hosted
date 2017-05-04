# Configure networks
class Networks
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    # Configure A Private Network IP
    config.vm.network :private_network, ip: settings['ip'] ||= "192.168.127.13"

    return unless settings.key?('networks')

    # Configure Additional Networks
    settings['networks'].each do |network|
      config.vm.network network['type'], ip: network['ip'], bridge: network['bridge'] ||= nil
    end
  end
end