<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer;

use League\Container\ContainerAwareInterface;
use Robo\Collection\CollectionBuilder;

trait PamaldComposerTaskLoader
{
    /**
     * @phpstan-param robo-pamald-composer-collect-packages-task-options $options
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
     * @phpstan-param robo-pamald-modify-commit-msg-parts-task-options $options
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
