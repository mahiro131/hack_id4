from __future__ import print_function
import socket
from contextlib import closing

def main():
	local_address   = '192.168.0.2'
	port = 4000
	bufsize = 4096

	sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
	
	with closing(sock):
		sock.bind((host, port))
		sock.listen(backlog)
		while True:
			conn, address = sock.accept()
			with closing(conn):
				msg = conn.recv(bufsize)
				print(msg)
				conn.send(msg)
	return

if __name__ == '__main__':
	main()
