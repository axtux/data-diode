#!/bin/sh

#Simulate cutting the fiber optic cable from the inside of the diode
# to the outside, by dropping all packets originating from the secure
# network and going to the intra-diode network
iptables -A OUTPUT -d 192.168.0.0/24 -j DROP
