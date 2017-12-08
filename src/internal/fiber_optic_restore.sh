#!/bin/sh
iptables -D OUTPUT -d 192.168.0.0/24 -j DROP
