# Configure Blackfire.io
class Blackfire
  attr_accessor :application_root, :config, :settings

  def initialize(application_root, config, settings)
    @application_root = application_root
    @config = config
    @settings = settings
  end

  def configure
    if settings['blackfire'] && settings['blackfire'][0]
      configure_blackfire settings['blackfire'][0]

      restart_blackfire
    end
  end

  private

  def configure_blackfire(bf)
    config.vm.provision :shell do |s|
      s.name = 'Configure Blackfire.io'
      s.inline = <<-EOF
          go-replace --mode=template \
            /vagrant/scripts/templates/.blackfire.ini:/home/vagrant/.blackfire.ini \
            /vagrant/scripts/templates/bf_agent:/etc/blackfire/agent
          chown vagrant:vagrant /home/vagrant/.blackfire.ini
      EOF
      s.env = blackfire_env(bf)
    end
  end

  def blackfire_env(bf)
    {
        TPL_BLACKFIRE_ID: bf['id'],
        TPL_BLACKFIRE_TOKEN: bf['token'],
        TPL_BLACKFIRE_CLIENT_ID: bf['client-id'],
        TPL_BLACKFIRE_CLIENT_TOKEN: bf['client-token']
    }
  end

  # Restart Blackfire agent
  def restart_blackfire
    config.vm.provision :shell do |s|
      s.name = 'Restart Blackfire agent'
      s.inline = 'service blackfire-agent restart'
    end
  end
end