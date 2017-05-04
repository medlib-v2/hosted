# Configure environment variables
class Variables
  attr_accessor :application_root, :config, :settings

  def initialize(application_root, config, settings)
    @application_root = application_root
    @config = config
    @settings = settings
  end

  def configure
    clear_variables

    return unless settings['variables']

    profile_vars = settings['variables'].map do |v|
      profile_var(v['key'], v['value'])
    end

    inject_variables(profile_vars, '/home/vagrant/.profile')

    fpm_vars = settings['variables'].map do |v|
      fpm_var(v['key'], v['value'])
    end

    inject_variables(fpm_vars, '/etc/php/7.1/fpm/php-fpm.conf')
  end

  private

  def inject_variables(vars, path)
    config.vm.provision :shell do |s|
      s.inline = <<-EOF
          printf "%s" "$1" >> $2
      EOF
      s.args = [vars.join("\n"), path]
    end
  end

  def clear_variables
    config.vm.provision :shell do |s|
      s.name = 'Clear environment variables'
      s.inline = <<-EOF
        sed -i '/# Hosted Box environment variable/,+1d' /home/vagrant/.profile
        sed -i '/; Hosted Box environment variable/,+1d' /etc/php/7.1/fpm/php-fpm.conf
      EOF
    end
  end

  def profile_var(key, value)
    "# Hosted Box environment variable\nexport #{key}=\"#{value}\""
  end

  def fpm_var(key, value)
    "; Hosted Box environment variable\nenv[#{key}]=\"#{value}\""
  end
end