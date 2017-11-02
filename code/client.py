#!/usr/bin/python

import sys
import socket

sys.argc = len(sys.argv)
if sys.argc < 4:
  print "usage: python", sys.argv[0], "ADDRESS PORT MESSAGE"
  sys.exit()

UDP_IP = sys.argv[1]
UDP_PORT = int(sys.argv[2])
MESSAGE = sys.argv[3]

sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM) # Internet/UDP
sock.sendto(MESSAGE, (UDP_IP, UDP_PORT))
sock.sendto(MESSAGE, (UDP_IP, UDP_PORT))

print 'Message "'+MESSAGE+'" sent!'
