														 
#===============================================================
#=======           Add containers to Networks     ==============
#===============================================================

################ Network : external-network ###################
sudo docker network connect --ip 200.0.0.10 external-network outside-machine
sudo docker network connect --ip 200.0.0.20 external-network diode-out 

################ Network : intra-diode      ###################
sudo docker network connect --ip 192.168.0.1 intra-diode diode-in 
sudo docker network connect --ip 192.168.0.2 intra-diode diode-out 

################ Network : secure-network   ###################
sudo docker network connect --ip 10.0.0.2 secure-network inside-machine
sudo docker network connect --ip 10.0.0.1 secure-network diode-in 



#                              +--------------------------------------------------+
#                              |                         |                        |
#                              |     +--------------+    |    +--------------+    |
#                              |     |              |    |    |              |    |
#                              |     |  192.168.0.2 +--------->  192.168.0.1 |    |
#                              |     |              |    |    |              |    |
#                              |     +-------^------+    |    +-------^------+    |
#                              |             |           |            |           |
# +---------------+            |     +-------v------+    |    +-------v------+    |               +--------------+
# |               |            |     |              |    |    |              |    |               |              |
# |   200.0.0.10  <------------------>  200.0.0.20  |    |    |   10.0.0.1   <-------------------->   10.0.0.2   |
# |               |            |     |              |    |    |              |    |               |              |
# +---------------+            |     +--------------+    |    +--------------+    |               +--------------+
#                              |                         |                        |
#                              |                         |                        |
#                              |                         |                        |
#                              +--------------------------------------------------+

