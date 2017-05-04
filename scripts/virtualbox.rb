# Configure VirtualBox
class Virtualbox
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    config.vm.provider :virtualbox do |vb|
      vb.name = settings['name']
      vb.gui = true if settings['gui']

      customize vb
    end
  end

  private

  def customize(vb)
    vb.customize [
      'modifyvm', :id,
      '--memory', settings['memory'],
      '--cpus', settings['cpus'],
      '--ioapic', 'on',
      '--natdnsproxy1', 'on',
      '--natdnshostresolver1', settings['natdnshostresolver'],
      '--ostype', 'Ubuntu_64'
    ]
  end
end