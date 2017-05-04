require_relative 'prober'

# Initialize user settings
class Settings
  DEFAULT_IP = '192.168.127.13'.freeze
  BOX_VERSION = '2.0.0'.freeze
  DEFAULT_CPUS = 1
  DEFAULT_MEMORY = 2048

  attr_accessor :application_root, :settings

  def initialize(application_root)
    @application_root = application_root

    load_file
    defaults
  end

  private

  def defaults
    settings['name']               ||= 'hosted'
    settings['box']                ||= 'laravel/homestead'
    settings['version']            ||= ">= #{BOX_VERSION}"
    settings['hostname']           ||= 'medlib.app'
    settings['ip']                 ||= DEFAULT_IP.to_s
    settings['natdnshostresolver'] ||= 'on'
    settings['vram']               ||= 100

    # at least 1 GB
    memory = setup_memory
    if memory.to_i < 1024
      memory = 1024
    end

    settings['memory'] = memory
    settings['cpus'] = setup_cpu
    settings['check_update'] = true
  end

  def load_file
    settings = {}
    yaml = application_root + '/../Settings.yml'
    json = application_root + '/../Settings.json'

    if File.exist?(yaml)
      settings = YAML.safe_load(File.read(yaml))
    elsif File.exist?(json)
      settings = JSON.parse(File.read(json))
    end

    settings ||= {}

    @settings = settings
  end

  def setup_cpu
    return DEFAULT_CPUS unless settings.key?('cpus')

    if settings['cpus'] =~ /auto/
      if Prober.mac?
        `sysctl -n hw.ncpu`.to_i
      elsif Prober.linux?
        `nproc`.to_i
      else
        DEFAULT_CPUS
      end
    else
      settings['cpus'].to_i
    end
  end

  def setup_memory
    return DEFAULT_MEMORY unless settings.key?('memory')

    if settings['memory'] =~ /auto/
      if Prober.mac?
        `sysctl -n hw.memsize`.to_i / 1024 / 1024 / 4
      elsif Prober.linux?
        `grep 'MemTotal' /proc/meminfo | sed -e 's/MemTotal://' -e 's/ kB//'`.to_i / 1024 / 4
      else
        DEFAULT_MEMORY
      end
    else
      settings['cpus'].to_i
    end
  end
end