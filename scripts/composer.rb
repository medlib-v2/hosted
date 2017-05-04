# Configure Composer
class Composer
  attr_accessor :config

  def initialize(config)
    @config = config
  end

  def configure
    config.vm.provision :shell do |s|
      s.name = 'Update Composer'
      s.inline = <<-EOF
        /usr/local/bin/composer self-update -q
        mkdir -p /home/vagrant/.composer
        chown -R vagrant:vagrant /home/vagrant/.composer
      EOF
    end
  end
end