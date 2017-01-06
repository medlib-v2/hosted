# Clear The Old Environment Variables

sed -i '/# Set Hosted Environment Variable/,+1d' /home/vagrant/.profile
sed -i '/env\[.*/,+1d' /etc/php/7.1/fpm/php-fpm.conf
