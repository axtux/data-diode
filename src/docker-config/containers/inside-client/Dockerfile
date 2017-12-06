FROM ubuntu:latest

#===============================================
#========         INSTALL             ==========
#===============================================
#Install php5, apache
RUN apt-get update && apt-get install -y php5
RUN apt-get install -y apache2

#Install ifconfig
RUN apt-get install -y net-tools

#Install ping (syntax : ping -c <number of paquets> <ip address>)
RUN apt-get install -y iputils-ping

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
#Launch this script when build is over
RUN chmod +x run.sh
CMD /run.sh
