<?php

namespace Cyve\Blockchain;

class Block
{
	public $timestamp;
	public $payload;
	public $hash;
	public $previousHash;
	public $pow;
	
	public function __construct($timestamp, $payload, $previousHash)
	{
		$this->timestamp = (int) $timestamp;
		$this->payload = base64_encode($payload);
		$this->previousHash = $previousHash;
		$this->hash = self::generateHash($this);
	}
	
	public static function generateHash($block)
	{
		return hash('sha256', $block->timestamp.'.'.$block->payload.'.'.$block->previousHash);
	}
}
