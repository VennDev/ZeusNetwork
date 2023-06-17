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

class Socket {
  
    private mixed $socket;
    private string $host;
    private int $port;
    private int|float $timeout;
    private bool $blocking;
    
    public function __construct(string $host, int $port, int $timeout = 4, bool $blocking = false) {
        $this->host = $host;
        $this->port = $port;
        $this->timeout = $timeout;
        $this->blocking = $blocking;
    }
    
    public function open() : void {
        $this->socket = @fsockopen('udp://'. $this->host, $this->port, $errno, $errstr, $this->timeout);
        stream_set_timeout($this->socket, $this->timeout);
        stream_set_blocking($this->socket, $this->blocking);
    }
    
    public function close() : void {
        fclose($this->socket);
    }
    
    public function write(string $command) : void {
        $length = \strlen($command);
        fwrite($this->socket, $command, $length);
    }
    
    public function read(int $length) : string {
        return fread($this->socket, $length);
    }
    
}