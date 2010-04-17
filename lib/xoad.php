<?php

defined('XOAD_BASE') or define('XOAD_BASE', dirname(__FILE__) . '/xoad/');

defined('XOAD_DEFAULT_SERIALIZER') or define('XOAD_DEFAULT_SERIALIZER', 'javascript');

require_once XOAD_BASE . 'exceptions.php';
require_once XOAD_BASE . 'event_emitter.php';
require_once XOAD_BASE . 'nodes.php';
require_once XOAD_BASE . 'inspector.php';
require_once XOAD_BASE . 'serializer.php';
