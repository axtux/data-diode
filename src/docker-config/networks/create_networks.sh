sudo docker network create --subnet=200.0.0.0/16 external-network
sudo docker network create --subnet=10.0.0.0/24 secure-network
sudo docker network create --subnet=196.168.0.0/24 intra-diode

sudo docker network ls
