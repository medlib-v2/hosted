# Donfigure dotfiles
class Dotfiles
  HOME_PATH = '/home/vagrant'.freeze

  attr_accessor :application_root, :config

  def initialize(application_root, config)
    @application_root = application_root
    @config = config
  end

  def configure
    try_copy('.inputrc')
    try_copy('.grcat')
    try_copy('.my.cnf')
    try_copy('.pgpass')
    try_copy('.mongorc.js')
  end

  private

  def try_copy(file)
    dest = File.join(HOME_PATH, file)

    src = File.join(application_root, "templates/#{file}")
    return unless File.exist?(src)

    config.vm.provision :shell, inline: "rm -f #{dest}"
    config.vm.provision :file, source: src, destination: dest
    config.vm.provision :shell, inline: "chmod 0600 #{dest}"
  end
end