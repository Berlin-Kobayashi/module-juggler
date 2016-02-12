#!/usr/bin/env bash

# This bootstrap was created by Module Juggler: https://github.com/DanShu93/module-juggler

# install colorful terminal
sed -i 's/^.*force_color_prompt=yes.*/force_color_prompt=yes/' /home/vagrant/.bashrc
source /home/vagrant/.bashrc

# install MongoDB
sudo apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv EA312927
echo "deb http://repo.mongodb.org/apt/ubuntu precise/mongodb-org/3.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.2.list
echo "deb http://repo.mongodb.org/apt/ubuntu trusty/mongodb-org/3.2 multiverse" | sudo tee /etc/apt/sources.list.d/mongodb-org-3.2.list
sudo apt-get update
sudo apt-get install -y mongodb-org

# install open MongoDB port for everyone
sudo iptables -A INPUT -p tcp --dport 27017 -j ACCEPT
sudo sed -i 's/^  bindIp: 127\.0\.0\.1$/#  bindIp: 127\.0\.0\.1/' /etc/mongod.conf
sudo service mongod restart

