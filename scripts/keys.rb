# Copy the SSH private keys to the box
class Keys
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    return unless settings['keys']

    settings['keys'].each do |key|
      config.vm.provision :shell do |s|
        s.privileged = false
        s.inline = <<-EOF
          echo "$1" > /home/vagrant/.ssh/$2
          chmod 600 /home/vagrant/.ssh/$2
        EOF
        s.args = [File.read(File.expand_path(key)), key.split('/').last]
      end
    end
  end
end