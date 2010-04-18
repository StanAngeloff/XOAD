<?php

defined('XOAD_BASE') or define('XOAD_BASE', dirname(__FILE__) . '/xoad/');

defined('XOAD_DEFAULT_INDENT') or define('XOAD_DEFAULT_INDENT', '  ');
defined('XOAD_DEFAULT_SERIALIZER') or define('XOAD_DEFAULT_SERIALIZER', 'javascript');
defined('XOAD_DEFAULT_CHUNK_SIZE') or define('XOAD_DEFAULT_CHUNK_SIZE', 16 * 1024 /* KB */);
defined('XOAD_DEFAULT_CHUNK_SLEEP') or define('XOAD_DEFAULT_CHUNK_SLEEP', 100 /* ms */);

require_once XOAD_BASE . 'exceptions.php';
require_once XOAD_BASE . 'event_emitter.php';
require_once XOAD_BASE . 'nodes.php';
require_once XOAD_BASE . 'inspector.php';
require_once XOAD_BASE . 'serializer.php';
require_once XOAD_BASE . 'server.php';
