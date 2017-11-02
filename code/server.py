#!/usr/bin/python

import sys
import socket

sys.argc = len(sys.argv)
if sys.argc < 3:
  print "usage: python", sys.argv[0], "ADDRESS PORT"
  sys.exit()

UDP_IP = sys.argv[1]
UDP_PORT = int(sys.argv[2])

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM) # Internet/UDP
sock.bind((UDP_IP, UDP_PORT))

print "Server listening on "+UDP_IP+":"+str(UDP_PORT)

while True:
  data, addr = sock.recvfrom(2048) # buffer size is 2048 bytes
  print "received message:", data
