#!/usr/bin/env bash

# This bootstrap was created by Module Juggler: https://github.com/DanShu93/module-juggler

# install colorful terminal
sed -i 's/^.*force_color_prompt=yes.*/force_color_prompt=yes/' /home/vagrant/.bashrc
source /home/vagrant/.bashrc

# install apache2
sudo apt-get update
sudo apt-get install -y apache2

# install web root link
if ! [ -L /var/www ]; then
  rm -rf /var/www/html
  ln -fs /vagrant/web /var/www/html
fi

# install apache2 rewrite module
sudo a2enmod rewrite
sudo service apache2 restart

# install PHP 5.6
sudo add-apt-repository -y ppa:ondrej/php5-5.6
sudo apt-get update
sudo apt-get install -y python-software-properties
sudo apt-get install -y php5

# install Git
sudo apt-get install -y git

# install common command line tools
git clone https://github.com/DanShu93/common-command-line-tools.git /home/vagrant/common-command-line-tools

# install .htaccess
sudo php /home/vagrant/common-command-line-tools/apache2/htaccessEnabler.php
sudo service apache2 restart

# install Composer
curl -sS https://getcomposer.org/installer | sudo -H php -- --install-dir=/usr/local/bin --filename=composer

# install Symfony installer
sudo curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony
sudo chmod a+x /usr/local/bin/symfony

# install node.js 5
curl -sL https://deb.nodesource.com/setup_5.x | sudo -E bash -
sudo apt-get install -y nodejs

# install MongoDB PHP extension
sudo apt-get install php5-mongo
sudo service apache2 restart

# install PHPUnit
sudo wget https://phar.phpunit.de/phpunit.phar
sudo chmod +x phpunit.phar
sudo mv phpunit.phar /usr/local/bin/phpunit

# composer install
cd /vagrant
sudo composer install

# npm install
cd /vagrant/web
sudo npm install

