#!/bin/bash

PREFIX="/usr/bin/"

install_jon() {
	sudo sh -c "curl --silent http://raw.github.com/roine/my_mvc_framework/master/scripts/jon.sh > ${PREFIX}jon"
	sudo chmod +x ${PREFIX}jon
}

#
# Handle execution
#
main() {

	# Start installation
	install_jon
	exit 0
}

main
