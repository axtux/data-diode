FROM ubuntu:latest

#===============================================
#========         INSTALL             ==========
#===============================================

#Need this to remove error messages at build time, see : https://github.com/phusion/baseimage-docker/issues/58
ENV DEBIAN-FRONTEND=noninteractive

# Install apt-utils that seems to be required
RUN apt-get update
# Install Mysql : we use /bin/bash otherwise the command create a 'redirection unexpected' error.
RUN /bin/bash -c "debconf-set-selections <<< 'mysql-server mysql-server/root_password password toto'"
RUN /bin/bash -c "debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password toto'"
RUN apt-get install -y --no-install-recommends apt-utils php7.0 php7.0-mysql nginx net-tools iputils-ping iptables make vim mysql-server


#===============================================
#========          Copy FILES         ==========
#===============================================
#Copy everything in the current directory and copy it in the container. 
COPY * /
 
COPY gen_config.php /var/www/html/
COPY server.php config.php config.json database.php index.php Makefile /var/www/html/
COPY default /etc/nginx/sites-available/


#===============================================
#========          Expose ports       ==========
#===============================================
#Expose the following ports
EXPOSE 80
 
#===============================================
#========         Launch Scripts      ==========
#===============================================
# Make scripts executable
RUN chmod +x run.sh
RUN chmod +x fiber_optic_cut.sh
RUN chmod +x fiber_optic_restore.sh
RUN chmod +x ping.sh

#Launch this script when build is over
CMD /run.sh
