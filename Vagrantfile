# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/trusty64"

  config.vm.network "private_network", ip: "192.168.50.4"

  config.ssh.forward_agent = true

  config.vm.synced_folder ".", "/var/www/htn", type: "nfs"

  config.hostmanager.enabled = true
  config.hostmanager.manage_host = true
  config.hostmanager.ignore_private_ip = false
  config.hostmanager.include_offline = true
  config.hostmanager.aliases = ["htn.dev"]

  config.vm.provider "virtualbox" do |v|
    v.customize ["modifyvm", :id, "--cpus", "1"]
    v.customize ["modifyvm", :id, "--memory", "2048"]
  end

  config.vm.provision :shell,
    :keep_color => true,
    :inline => "export PYTHONUNBUFFERED=1 && export ANSIBLE_FORCE_COLOR=1 && cd /var/www/htn/provision && ./ansible.sh"
end
