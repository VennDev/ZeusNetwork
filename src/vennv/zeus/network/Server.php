<?php

/**
 * ZeusProtocolLib
 * Copyright (C) 2023 - 2025 VennDev
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace vennv\zeus\network;

class Server {

    private string $host;
    private int $port;

    public function __construct(string $host, int $port) {
        $this->host = $host;
        $this->port = $port;
    }

    public function send(string $command) : void {
        $socket = @fsockopen('udp://'. $this->host, $this->port, $errno, $errstr, 4);
        stream_set_timeout($socket, 4);
        stream_set_blocking($socket, false);
        $length = \strlen($command);
        fwrite($socket, $command, $length);
        fread($socket, 4096);
        fclose($socket);
    }

    public function listen() : mixed {
        set_time_limit(0);
        $socket = socket_create(AF_INET, SOCK_DGRAM, 0) or die("Could not create socket\n");
        socket_bind($socket, $this->host, $this->port) or die("Could not bind to socket\n");
        socket_recvfrom($socket, $buffer, 512, 0, $remote_ip, $remote_port);   
        $data = json_decode($buffer);
		socket_close($socket);
        return $data;
    }

    public function getHost() : string {
        return $this->host;
    }

    public function getPort() : int {
        return $this->port;
    }
    
}