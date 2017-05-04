# Configure VMware WorkStation
class VMwareWorkStation
  attr_accessor :vmware, :config, :settings

  def initialize(vmware, config, settings)
    @vmware = vmware
    @config = config
    @settings = settings
  end

  def configure
    config.vm.provider vmware do |vm|
      vm.vmx['displayName'] = settings['name']
      vm.vmx['memsize'] = settings['memory']
      vm.vmx['numvcpus'] = settings['cpus']
      vm.vmx['guestOS'] = 'ubuntu-64'
      vm.gui = true if settings['gui']
    end
  end
end