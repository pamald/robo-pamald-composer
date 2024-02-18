<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer\Task;

use Pamald\Pamald\PackageCollectorInterface;
use Pamald\PamaldComposer\PackageCollector;
use Pamald\Robo\Pamald\Task\ModifyCommitMsgPartsTaskBase;

class ModifyCommitMsgPartsTask extends ModifyCommitMsgPartsTaskBase
{
    protected string $taskName = 'pamald - Composer - Modify commit message parts';

    protected string $packageManagerName = 'composer';

    /**
     * {@inheritdoc}
     */
    protected array $patterns = [
        // Standard name in the project root or in any sub-directory.
        'composer.lock',
        '**/composer.lock',

        // Optional composer-suite name variants.
        'composer.*.lock',
        '**/composer.*.lock',
    ];

    protected function getJsonFilePath(string $lockFilePath): string
    {
        return preg_replace('@\.lock$@', '.json', $lockFilePath);
    }

    protected function getPackageCollector(): PackageCollectorInterface
    {
        return new PackageCollector();
    }

    protected function isDomesticated(string $lockFilePath): bool
    {
        return str_starts_with(
            pathinfo($lockFilePath, \PATHINFO_BASENAME),
            'composer.',
        );
    }
}
