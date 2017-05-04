# Configure VMware WorkStation
class Parallels
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    config.vm.provider :parallels do |vm|
      vm.name = settings['name']
      vm.update_guest_tools['memsize'] = true
      vm.memory = settings['memory']
      vm.cpus = settings['cpus']
    end
  end
end