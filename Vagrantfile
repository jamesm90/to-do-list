# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.box = "geerlingguy/centos7"

    config.vm.network "public_network", bridge: "en1: Wi-Fi (AirPort)"

    config.vm.provision "ansible" do |ansible|
        ansible.playbook = "ansible/provision.yml"
    end

    config.vm.synced_folder "src", "/var/www/todolist/src",
        mount_options: ["dmode=775,fmode=664"]

    config.vm.synced_folder "app", "/var/www/todolist/app",
        mount_options: ["dmode=775,fmode=664"]

    config.vm.synced_folder "maintenance", "/var/www/todolist/maintenance",
        mount_options: ["dmode=775,fmode=664"]

    config.vm.synced_folder "vendor", "/var/www/todolist/vendor",
        mount_options: ["dmode=775,fmode=664"]

    config.vm.synced_folder "web", "/var/www/todolist/web",
        mount_options: ["dmode=775,fmode=664"]
end
