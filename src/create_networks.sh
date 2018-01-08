sudo docker network create --subnet=10.10.10.0/24 external-network
sudo docker network create --subnet=10.42.42.0/24 secure-network
sudo docker network create --subnet=169.254.42.0/24 intra-diode

sudo docker network ls
