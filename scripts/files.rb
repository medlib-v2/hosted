# Copy user files over to VM
class Files
  attr_accessor :config, :settings

  def initialize(config, settings)
    @config = config
    @settings = settings
  end

  def configure
    return unless settings['copy']

    settings['copy'].each do |file|
      config.vm.provision :file do |f|
        f.source = File.expand_path(file['from'])
        f.destination = File.join file['to'], File.basename(file['from'])
      end
    end
  end
end