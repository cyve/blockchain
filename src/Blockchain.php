<?php
/**
 * @author Cyril Vermande (cyril@cyrilwebdesign.com)
 * @license MIT
 * @copyright All rights reserved 2018 Cyril Vermande
 */

namespace Cyve\Blockchain;

class Blockchain
{
    const DIFFICULTY = 2;

    public $blocks = [];

    public function __construct()
    {
        // create first block
        $this->blocks[] = new Block(microtime(true), 'init', null);
    }

    public function isValidFirstBlock(Block $block)
    {
        return base64_decode($block->payload) === 'init'
            // block has no previous hash
            && $block->previousHash === null
            // block's hash matches its content
            && $block->generateHash() === $block->hash;
    }

    public function isValidBlock(Block $block, Block $previous)
    {
        return $block->timestamp >= $previous->timestamp
            // block's hash matches previous block's hash
            && $block->previousHash === $previous->hash
            // block's hash matches its content
            && $block->generateHash() === $block->hash
            // block's hash validates proof of work
            && $this->validateProofOfWork($block, self::DIFFICULTY);
    }

    public function isValid()
    {
        // first block is valid
        if(!$this->isValidFirstBlock($this->blocks[0])) return false;

        // each block is valid
        for($i=1,$l=count($this->blocks); $i<$l; $i++){
            if(!$this->isValidBlock($this->blocks[$i], $this->blocks[$i-1])) return false;
        }

        return true;
    }

    public function add(Block $block)
    {
        // add block to the blockchain
        $this->blocks[] = $block;
    }

    public function createNewBlock($payload)
    {
        // create new block after blockchain's last block
        $block = new Block(microtime(true), $payload, end($this->blocks)->hash);

        // create proof of work
        while(!$this->validateProofOfWork($block, self::DIFFICULTY)){
            $block->nonce++;
            $block->hash = $block->generateHash();
        }

        return $block;
    }

    public function validateProofOfWork(Block $block, int $difficulty){
        // block's hash starts with a number of '0' equals to difficulty
        return substr($block->hash, 0, $difficulty) === str_pad('', $difficulty, '0');
    }
}
