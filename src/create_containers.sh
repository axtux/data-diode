
# Building container : inner side of the diode
cd internal/
sudo docker build . -t diode_in-img
sudo docker run -t -d --net intra-diode --ip 192.168.0.2  -p 8880:80 --name diode-in diode_in-img
cd -

# Building container : outter side of the diode
cd external/
sudo docker build . -t diode_out-img 
sudo docker run -t -d --net intra-diode --ip 192.168.0.3 -p 8881:80 -p 8882:443 --name diode-out diode_out-img
cd -

# Building container : machine on the outside network. FTP client, stream client. 
#cd outside-client/
#sudo docker build . -t outside_network-img
#sudo docker run -t -d --net external-network --ip 200.0.0.3 --name outside-machine outside_network-img
#cd -

# Building container : machine on the inside network.
#cd inside-client/
#sudo docker build . -t inside_network-img
#sudo docker run -t -d --net secure-network --ip 10.0.0.3 --name inside-machine inside_network-img
#cd -


# Creating connecting a part of the diode to the outside, and the other one to the inside
sudo docker network connect --ip 10.0.0.2 secure-network diode-in
sudo docker network connect --ip 200.0.0.2 external-network diode-out
