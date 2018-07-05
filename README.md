# To Do List

Requirements:
- Vagrant
- Composer
- Ansible
- PHP 7.2
- VirtualBox

Main technologies:
- Symfony2
- MySQL
- Doctrine ORM
- jQuery
- Bootstrap

## Running the app

__NOTE__ Requires Vagrant and VirtualBox. Tested against Vagrant 1.9.1 and VirtualBox 5.1.

1. Checkout the repository
2. Run `composer install` to get dependencies
3. Run `vagrant up`
4. Once the vagrant box finishes provisioning, SSH to the box `vagrant ssh` and run `ifconfig`. Make a note of the inet address entry starting `192.168.X.X`
5. On the host machine, edit the host file, and add an entry for `todolist.local` against the above IP
6. Open a browser, and navigate to https://todolist.local

The vagrant box runs CentOS7, PHP 7.2 and MariaDB.

## Running unit tests

From the project root, run `vendor/bin/phpunit tests/unit` on the host machine.