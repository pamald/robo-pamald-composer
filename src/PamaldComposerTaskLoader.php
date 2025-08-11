<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer;

use League\Container\ContainerAwareInterface;
use Robo\Collection\CollectionBuilder;

/**
 * @phpstan-import-type RoboPamaldComposerCollectPackagesTaskOptions from \Pamald\Robo\PamaldComposer\Phpstan
 * @phpstan-import-type RoboPamaldComposerModifyCommitMsgPartsTaskOptions from \Pamald\Robo\PamaldComposer\Phpstan
 */
trait PamaldComposerTaskLoader
{
    /**
     * @phpstan-param RoboPamaldComposerCollectPackagesTaskOptions $options
     *
     * @return \Pamald\Robo\PamaldComposer\Task\CollectComposerPackagesTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPamaldCollectComposerPackages(array $options = []): CollectionBuilder
    {
        /** @var \Pamald\Robo\PamaldComposer\Task\CollectComposerPackagesTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\CollectComposerPackagesTask::class);
        $task->setOptions($options);

        return $task;
    }

    /**
     * @phpstan-param RoboPamaldComposerModifyCommitMsgPartsTaskOptions $options
     *
     * @return \Pamald\Robo\PamaldComposer\Task\ModifyCommitMsgPartsTask|\Robo\Collection\CollectionBuilder
     */
    protected function taskPamaldComposerModifyCommitMsgParts(array $options = []): CollectionBuilder
    {
        /** @var \Pamald\Robo\PamaldComposer\Task\ModifyCommitMsgPartsTask|\Robo\Collection\CollectionBuilder $task */
        $task = $this->task(Task\ModifyCommitMsgPartsTask::class);
        if ($this instanceof ContainerAwareInterface) {
            $task->setContainer($this->getContainer());
        }

        $task->setOptions($options);

        return $task;
    }
}
