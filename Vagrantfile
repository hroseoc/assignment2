# -*- mode: ruby -*-
# vi: set ft=ruby :

# A Vagrantfile to set up 3 VMs, 2 webservers and a database server,
# connected together using an internal network with manually-assigned
# IP addresses for the VMs.

Vagrant.configure("2") do |config|

    config.vm.box = "ubuntu/xenial64"
  
    # Defines a particular named VM, which is necessary when Vagrantfile will start up multiple interconnected VMs.
    config.vm.define "clientserver" do |clientserver|
      # These are options specific to the webserver VM
      clientserver.vm.hostname = "clientserver"
      
      clientserver.vm.network "forwarded_port", guest: 80, host: 8080, host_ip: "127.0.0.1"
      
      clientserver.vm.network "private_network", ip: "192.168.2.11"

      clientserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
  
      # Section specifying the shell commands to provision the webserver VM. 
      clientserver.vm.provision "shell", inline: <<-SHELL
        apt-get update
        apt-get install -y apache2 php libapache2-mod-php php-mysql
              
        # Change VM's webserver's configuration to use shared folder.
        # (Look inside test-website.conf for specifics.)
        cp /vagrant/client-side.conf /etc/apache2/sites-available/
        # activate our website configuration ...
        a2ensite client-side
        # ... and disable the default website provided with Apache
        a2dissite 000-default
        # Reload the webserver configuration, to pick up our changes
        service apache2 reload
      SHELL
    end

    config.vm.define "adminserver" do |adminserver|
        # These are options specific to the webserver VM
        adminserver.vm.hostname = "adminserver"
        
        adminserver.vm.network "forwarded_port", guest: 80, host: 8081, host_ip: "127.0.0.1"
        
        # Set up a private network that our VMs will use to communicate
        # with each other. 
        adminserver.vm.network "private_network", ip: "192.168.2.13"
    
        # This following line is only necessary in the CS Labs... but that
        # may well be where markers mark your assignment.
        adminserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
    
        # Shell commands to provision the webserver VM. Note that the file test-website.conf is copied
        # from this host to the VM through the shared folder mounted in the VM at /vagrant
        adminserver.vm.provision "shell", inline: <<-SHELL
          apt-get update
          apt-get install -y apache2 php libapache2-mod-php php-mysql
                
          # Change VM's webserver's configuration to use shared folder.
          # (Look inside test-website.conf for specifics.)
          cp /vagrant/admin-side.conf /etc/apache2/sites-available/
          # activate our website configuration ...
          a2ensite admin-side
          # ... and disable the default website provided with Apache
          a2dissite 000-default
          # Reload the webserver configuration, to pick up our changes
          service apache2 reload
        SHELL
      end
  
    # Defining the database server "dbserver".
    config.vm.define "dbserver" do |dbserver|
      dbserver.vm.hostname = "dbserver"
      # Note that the IP address is different from that of the webserver
      # above: it is important that no two VMs attempt to use the same
      # IP address on the private_network.
      dbserver.vm.network "private_network", ip: "192.168.2.12"
      dbserver.vm.synced_folder ".", "/vagrant", owner: "vagrant", group: "vagrant", mount_options: ["dmode=775,fmode=777"]
      
      dbserver.vm.provision "shell", inline: <<-SHELL
        # Update Ubuntu software packages.
        apt-get update
        
        # We create a shell variable MYSQL_PWD that contains the MySQL root password
        export MYSQL_PWD='insecure_mysqlroot_pw'
  
      
        echo "mysql-server mysql-server/root_password password $MYSQL_PWD" | debconf-set-selections 
        echo "mysql-server mysql-server/root_password_again password $MYSQL_PWD" | debconf-set-selections
  
        # Install the MySQL database server.
        apt-get -y install mysql-server
  
        # Run some setup commands to get the database ready to use.
        # First create a database.
        echo "CREATE DATABASE booking;" | mysql
  
        # Then create a database user "webuser" with the given password.
        echo "CREATE USER 'webuser'@'%' IDENTIFIED BY 'insecure_db_pw';" | mysql
  
        # Grant all permissions to the database user "webuser" regarding
        # the "order" database that we just created, above.
        echo "GRANT ALL PRIVILEGES ON booking.* TO 'webuser'@'%'" | mysql
        
        # Set the MYSQL_PWD shell variable that the mysql command will
        # try to use as the database password ...
        export MYSQL_PWD='insecure_db_pw'
  
       #The mysql command specifies both the user to connect as (webuser) and the database to use (order).
        cat /vagrant/setup-database.sql | mysql -u webuser booking

        sed -i'' -e '/bind-address/s/127.0.0.1/0.0.0.0/' /etc/mysql/mysql.conf.d/mysqld.cnf
  
        # We then restart the MySQL server to ensure that it picks up
        # our configuration changes.
        service mysql restart
      SHELL
    end
  
  end

  