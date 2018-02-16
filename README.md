# blockchain

```
<?php
include __DIR__.'/../vendor/autoload.php';

use Cyve\Blockchain\Block;
use Cyve\Blockchain\Blockchain;

$blockchain = new Blockchain();

// Add blocks
$blockchain->add($blockchain->createNewBlock('Block 1'));
$blockchain->add($blockchain->createNewBlock('Block 2'));
$valid = $blockchain->isValid(); //true

// Insert falsified block
$original = $blockchain->blocks[1];
$falsified = new Block($original->timestamp, 'Falsified Block 1', $original->previousHash);
$blockchain->blocks[1] = $falsified;
$valid = $blockchain->isValid(); //false
```

TL;DR : the "mining" function is the `while` loop in the `Blockchain::createNewBlock()` method ;-)
