# Register all of the configured shared folders
class Folders
  attr_accessor :application_root, :config, :settings

  def initialize(application_root, config, settings)
    @application_root = application_root
    @config = config
    @settings = settings
  end

  def configure
    return unless settings['folders']

    settings['folders'].each do |folder|
      from = File.expand_path(folder['map'])
      if File.exist? from
        user_folder(folder)
      else
        notify(from)
      end
    end
  end

  private

  def user_folder(folder)
    mount_options = { mount_options: prepare_option(folder) }
    options = (folder['options'] || {}).merge(mount_options)

    # Double-splat (**) operator only works with symbol keys
    options.keys.each { |k| options[k.to_sym] = options.delete(k) }

    config.vm.synced_folder folder['map'],
                            folder['to'],
                            type: folder['type'] ||= nil,
                            **options

    # Bindfs support to fix shared folder (NFS) permission issue on macOS
    return unless Vagrant.has_plugin?('vagrant-bindfs')
    config.bindfs.bind_folder folder['to'], folder['to']
  end

  def notify(from)
    config.vm.provision :shell do |s|
      s.inline = <<-EOF
        >&2 echo "Unable to mount '$1' folder"
        >&2 echo "Please check your folders in Settings.yaml or Settings.json "
      EOF
      s.args = [from]
    end
  end

  def prepare_option(folder)
    if folder['type'] == 'nfs'
      folder['options'] || %w[actimeo=1 nolock]
    elsif folder['type'] == 'smb'
      folder['options'] || %w[vers=3.02 mfsymlinks]
    else
      []
    end
  end
end