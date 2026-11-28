<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\If_\ExplicitNullableObjectInstanceRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([__DIR__.'/src', __DIR__.'/tests'])
    ->withRootFiles()
    ->withPHPStanConfigs([__DIR__.'/phpstan-rector.neon'])
    ->withPhpSets(php84: true)
    ->withPreparedSets(codeQuality: true)
    ->withSkip([
        ExplicitNullableObjectInstanceRector::class,
    ]);
