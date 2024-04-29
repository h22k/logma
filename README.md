
[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)




# Logma

Logma is a simple logging tool. It's name coming from combination of Turkish word _Lokma_ and _log_.

## Install
`composer require h22k/logma`

## Usage/Examples
TODO: More examples will be added.



```php
<?php

use H22k\Logma\Formatter\PlainTextFormatter;
use H22k\Logma\Logma;
use H22k\Logma\Resource\StreamResource;
use H22k\Logma\Source\StreamSource;

$logma = new Logma(
    'logma',
);

$source = new StreamSource(
    new StreamResource(__DIR__.'/storage/test.log'), 
    new PlainTextFormatter());

$logma->addSource($source);

$logma->info('first log'); 
// OUTPUT: [29/04/24 07:18:48] logmaTest.INFO | first log a:0:{}
```
