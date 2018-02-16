<?php

namespace Cyve\Blockchain;

class Block
{
    public $timestamp;
    public $payload;
    public $hash;
    public $previousHash;
    public $nonce = 0;
    
    public function __construct($timestamp, $payload, $previousHash)
    {
        $this->timestamp = (int) $timestamp;
        $this->payload = base64_encode($payload);
        $this->previousHash = $previousHash;
        $this->hash = $this->generateHash();
    }
    
    public function generateHash()
    {
        return hash('sha256', $this->timestamp.'.'.$this->payload.'.'.$this->previousHash.'.'.$this->nonce);
    }
}
