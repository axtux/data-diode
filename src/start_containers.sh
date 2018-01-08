
# Building container : inner side of the diode
cd internal/
sudo docker build . -t diode_in-img
sudo docker run -t -d --net intra-diode --ip 169.254.42.2 -p 8880:80 --name diode-in --cap-add=NET_ADMIN --cap-add=NET_RAW diode_in-img
cd -

# Building container : outter side of the diode
cd external/
sudo docker build . -t diode_out-img
sudo docker run -t -d --net intra-diode --ip 169.254.42.3 -p 8881:80 -p 8882:443 --name diode-out diode_out-img
cd -

# Creating connecting a part of the diode to the outside, and the other one to the inside
sudo docker network connect --ip 10.42.42.2 secure-network diode-in
sudo docker network connect --ip 10.10.10.2 external-network diode-out
