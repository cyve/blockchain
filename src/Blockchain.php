<?php

namespace Cyve\Blockchain;

class Blockchain
{
	const DIFFICULTY = 2;
	
	public $blocks = [];
	
	public function __construct()
	{
		$this->blocks[] = new Block(microtime(true), 'init', null);
	}
	
	public function isValidFirstBlock(Block $block)
	{
		return base64_decode($block->payload) === 'init' && $block->previousHash === null && $block->generateHash() === $block->hash;
	}
	
	public function isValidBlock(Block $block, Block $previous)
	{
		return $block->timestamp >= $previous->timestamp && $block->previousHash === $previous->hash && $block->generateHash() === $block->hash && $this->validateProofOfWork($block, self::DIFFICULTY);
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
		$block = new Block(microtime(true), $payload, end($this->blocks)->hash);
		
		while(!$this->validateProofOfWork($block, self::DIFFICULTY)){
			$block->nonce++;
			$block->hash = $block->generateHash();
		}
		
		return $block;
	}
	
	public function validateProofOfWork(Block $block, int $difficulty){
		return substr($block->hash, 0, $difficulty) === str_pad('', $difficulty, '0');
	}
}
