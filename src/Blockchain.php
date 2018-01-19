<?php

namespace Cyve\Blockchain;

class Blockchain
{
	public $blocks = [];
	
	public function __construct($filename = null)
	{
		$this->blocks[] = new Block(microtime(true), 'init', null);
	}
	
	public function isValidFirstBlock($block)
	{
		return base64_decode($block->payload) === 'init' && $block->previousHash === null && Block::generateHash($block) === $block->hash;
	}
	
	public function isValidBlock($block, $previous)
	{
		return $block->timestamp >= $previous->timestamp && $block->previousHash === $previous->hash && Block::generateHash($block) === $block->hash;
	}
	
	public function isValid()
	{
		if(!$this->isValidFirstBlock($this->blocks[0])) return false;
		
		for($i=1,$l=count($this->blocks); $i<$l; $i++){
			if(!$this->isValidBlock($this->blocks[$i], $this->blocks[$i-1])) return false;
		}
		
		return true;
	}
	
	public function add(Block $block)
	{
		$this->blocks[] = $block;
	}
	
	public function createNewBlock($payload)
	{
		return new Block(microtime(true), $payload, end($this->blocks)->hash);
	}
}
