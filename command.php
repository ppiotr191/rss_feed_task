<?php

require "vendor/autoload.php";

\RSSPackage\Log::startScript();
$manager = new RSSPackage\Manager();
$manager->start();
\RSSPackage\Log::finishScript();