# Configure Message of the Day
class Motd
  attr_accessor :application_root, :config, :settings

  def initialize(application_root, config, settings)
    @application_root = application_root
    @config = config
    @settings = settings
  end

  def configure
    config.vm.provision :shell do |s|
      s.name = 'Configure Message of the Day'
      s.inline = <<-EOF
        rm -f /etc/update-motd.d/00-header /etc/update-motd.d/10-help-text
        cp -f /vagrant/scripts/templates/00-header /etc/update-motd.d/00-header
        chmod +x /etc/update-motd.d/00-header
      EOF
    end
  end
end