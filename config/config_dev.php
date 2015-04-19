<?php

require_once __DIR__ . '/config_prod.php';

$parameters = require __DIR__ . '/parameters.php';

$container['collectors'] = $parameters['collectors'];
$container['modifiers'] = $parameters['modifiers'];
$container['forwarders'] = $parameters['forwarders'];