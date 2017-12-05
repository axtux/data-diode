
# Building container : inner side of the diode
sudo docker build . -t diode_in-img
sudo docker run -d --name diode_in --net secure-network

# Building container : outter side of the diode
sudo docker build . -t diode_out-img 
sudo docker run -d --name diode_out --net external-network

# Building container : machine on the outside network. FTP client, stream client. 
sudo docker build . -t outside_network-img
sudo docker run -d --name outside_machine --net external-network

# Building container : machine on the inside network.
sudo docker build . -t inside_network-img
sudo docker run -d --name inside_machine --net secure-network
