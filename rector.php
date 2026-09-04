<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Identical\FlipTypeControlToUseExclusiveTypeRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__.'/src', __DIR__.'/tests'])
    ->withRootFiles()
    ->withPHPStanConfigs([__DIR__.'/phpstan-rector.neon'])
    ->withPhpSets(php83: true)
    ->withPreparedSets(codeQuality: true)
    ->withSkip([
        FlipTypeControlToUseExclusiveTypeRector::class,
    ]);
