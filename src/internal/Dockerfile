FROM ubuntu:latest

#===============================================
#========         INSTALL             ==========
#===============================================

#Need this to remove error messages at build time, see : https://github.com/phusion/baseimage-docker/issues/58
ENV DEBIAN-FRONTEND=noninteractive

# Install apt-utils that seems to be required
RUN apt-get update
RUN apt-get install -y --no-install-recommends apt-utils

#Install php7, nginx 
RUN apt-get install -y php7.0
RUN apt-get install -y nginx 

#Install ifconfig
RUN apt-get install -y net-tools

#Install ping (syntax : ping -c <number of paquets> <ip address>)
RUN apt-get install -y iputils-ping

#Install iptables
RUN apt-get install -y iptables

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
#========         Launch Scripts      ==========
#===============================================
# Make scripts executable
RUN chmod +x run.sh
RUN chmod +x fiber_optic_cut.sh
RUN chmod +x fiber_optic_restore.sh
RUN chmod +x ping.sh

#Launch this script when build is over
CMD /run.sh