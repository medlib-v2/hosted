# Configure all of the configured databases
class Database
  attr_accessor :application_root, :config, :settings

  def initialize(application_root, config, settings)
    @application_root = application_root
    @config = config
    @settings = settings
  end

  def configure
    return unless settings.key?('databases')

    settings['databases'].each do |db|
      mysql(db)
      postgres(db)
      mongo(db)
    end
  end

  private

  def mysql(db)
    config.vm.provision :shell do |s|
      s.name = "Creating MySQL Database: #{db}"
      s.inline = <<-EOF
        cp -f /vagrant/scripts/templates/.my.cnf /root/.my.cnf
        mysql -e "CREATE DATABASE IF NOT EXISTS $1 DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci"
      EOF
      s.args = [db]
    end
  end

  def postgres(db)
    config.vm.provision :shell do |s|
      s.name = "Creating Postgres Database: #{db}"
      s.path = "#{application_root}/provision/postgres.sh"
      s.args = [db]
    end
  end

  def mongo(db)
    config.vm.provision :shell do |s|
      s.name = "Creating Mongo Database: #{db}"
      s.inline = 'mongo admin --eval "$1"'
      s.args = [
          File.read("#{application_root}/templates/mongo_user.js")
      ]
      s.inline = 'mongo $1 --quiet --eval "$2"'
      s.args = [
          db,
          File.read("#{application_root}/templates/mongo_db.js")
      ]
    end
  end
end