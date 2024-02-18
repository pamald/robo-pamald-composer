<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer\Task;

use Pamald\PamaldComposer\PackageCollector;

class CollectComposerPackagesTask extends TaskBase
{

    protected string $taskName = 'pamald - Collect Composer packages';

    // region collector
    protected ?PackageCollector $collector = null;

    public function getCollector(): ?PackageCollector
    {
        return $this->collector;
    }

    protected function getCollectorFinal(): PackageCollector
    {
        return $this->getCollector() ?: new PackageCollector();
    }

    public function setCollector(?PackageCollector $collector): static
    {
        $this->collector = $collector;

        return $this;
    }
    // endregion

    // region lock
    /**
     * @var null|array<string, mixed>
     */
    protected ?array $lock = null;

    /**
     * @return null|array<string, mixed>
     */
    public function getLock(): ?array
    {
        return $this->lock;
    }

    /**
     * @param null|array<string, mixed> $lock
     */
    public function setLock(?array $lock): static
    {
        $this->lock = $lock;

        return $this;
    }
    // endregion

    // region json
    /**
     * @var null|array<string, mixed>
     */
    protected ?array $json = null;

    /**
     * @return null|array<string, mixed>
     */
    public function getJson(): ?array
    {
        return $this->json;
    }

    /**
     * @param null|array<string, mixed> $json
     */
    public function setJson(?array $json): static
    {
        $this->json = $json;

        return $this;
    }
    // endregion

    /**
     * {@inheritdoc}
     *
     * @phpstan-param robo-pamald-composer-collect-packages-task-options $options
     */
    public function setOptions(array $options): static
    {
        parent::setOptions($options);

        if (array_key_exists('collector', $options)) {
            $this->setCollector($options['collector']);
        }

        if (array_key_exists('lock', $options)) {
            $this->setLock($options['lock']);
        }

        if (array_key_exists('json', $options)) {
            $this->setJson($options['json']);
        }

        return $this;
    }

    protected function runHeader(): static
    {
        $this->printTaskInfo('Collect Composer packages');

        return $this;
    }

    protected function runDoIt(): static
    {
        $this->assets['pamald.composerPackages'] = $this
            ->getCollectorFinal()
            ->collect($this->getLock(), $this->getJson());

        return $this;
    }
}
