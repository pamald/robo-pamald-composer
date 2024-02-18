<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer\Tests\Unit\Task;

use Pamald\Robo\PamaldComposer\Task\CollectComposerPackagesTask;
use Pamald\Robo\PamaldComposer\Task\TaskBase;
use Pamald\Robo\PamaldComposer\PamaldComposerTaskLoader;
use Pamald\Robo\PamaldComposer\Tests\Helper\DummyTaskBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;

#[CoversClass(CollectComposerPackagesTask::class)]
#[CoversClass(TaskBase::class)]
class CollectComposerPackagesTaskTest extends TaskTestBase
{
    /**
     * @return resource
     */
    protected static function createStream()
    {
        $filePath = 'php://memory';
        $resource = fopen($filePath, 'rw');
        if ($resource === false) {
            throw new \RuntimeException("file $filePath could not be opened");
        }

        return $resource;
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesRunSuccess(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'exitCode' => 0,
                    'exitMessage' => '',
                    'assets' => [
                        'pamald.composerPackages' => [
                            'a/a' => [],
                            'b/a' => [],
                        ],
                    ],
                ],
                'options' => [
                    'lock' => [
                        'packages' => [
                             [
                                 'name' => 'a/a',
                                 'version' => '1.0.0',
                             ],
                        ],
                        'packages-dev' => [
                             [
                                 'name' => 'b/a',
                                 'version' => '2.0.0',
                             ],
                        ],
                    ],
                    'json' => [
                        'require' => [
                            'a/a' => '^1.0',
                        ],
                        'require-dev' => [
                            'b/a' => '^2.0',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @phpstan-param array<string, mixed> $expected
     * @phpstan-param robo-pamald-composer-collect-packages-task-options $options
     */
    #[DataProvider('casesRunSuccess')]
    public function testRunSuccess(array $expected, array $options): void
    {
        $taskBuilder = new DummyTaskBuilder();
        $taskBuilder->setContainer($this->getNewContainer());

        $task = $taskBuilder->taskPamaldCollectComposerPackages($options);
        $result = $task->run();

        static::assertSame($expected['exitCode'], $result->getExitCode());
        static::assertSame($expected['exitMessage'], $result->getMessage());
        static::assertSame(
            array_keys($expected['assets']['pamald.composerPackages']),
            array_keys($result['pamald.composerPackages']),
        );
    }
}
