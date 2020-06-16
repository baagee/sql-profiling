<?php

require_once implode(DIRECTORY_SEPARATOR, [dirname(__DIR__), 'vendor', 'autoload.php']);

(new \BaAGee\NkNkn\HttpApp())->run();

