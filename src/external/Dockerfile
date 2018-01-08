FROM ubuntu:latest

#===============================================
#========         INSTALL             ==========
#===============================================
#Need this to remove error messages at build time, see : https://github.com/phusion/baseimage-docker/issues/58
ENV DEBIAN-FRONTEND=noninteractive

# Install apt-utils that seems to be required
RUN apt-get update
RUN apt-get install -y --no-install-recommends apt-utils php7.0 nginx net-tools iputils-ping make vim

#===============================================
#========          Copy FILES         ==========
#===============================================
#Copy everything in the current directory and copy it in the container. 
COPY * /
 
#===============================================
#========          Expose ports       ==========
#===============================================
#Expose the following ports
EXPOSE 80
 
#===============================================
#========      Configure Nginx        ==========
#===============================================
COPY default /etc/nginx/sites-available/
COPY index.php config.php config.json admin.php gen_config.php /var/www/html

#===============================================
#========         Launch Scripts      ==========
#===============================================
# Make scripts executable
RUN chmod +x run.sh
RUN chmod +x ping.sh
RUN chmod +x initialize_web.sh

#Launch this script when build is over
CMD /run.sh
