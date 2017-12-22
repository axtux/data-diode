sudo docker network create --subnet=200.0.0.0/16 outside-network
sudo docker network create --subnet=10.0.0.0/24 secure-network
sudo docker network create --subnet=192.168.0.0/24 intra-diode
