Vagrant.configure("2") do |config|

  config.vm.define "web" do |web|
     web.vm.box = "ubuntu/trusty64"
     web.vm.provision :shell, path: "vagrant/bootstrap.sh"
     web.vm.network "private_network", ip: "192.168.1.6"
     web.vm.provider "virtualbox" do |v|
       v.memory = 2048
       v.cpus = 2
     end
  end

  config.vm.define "db" do |db|
    db.vm.box = "ubuntu/trusty64"
    db.vm.provision :shell, path: "vagrant/mongodb-server/bootstrap.sh"
    db.vm.network "private_network", ip: "192.168.1.7"
  end
end
