<?php

declare(strict_types = 1);

namespace Pamald\Robo\PamaldComposer\Tests\Acceptance\Task;

class ReportTaskTest extends TaskTestBase
{
    public function testRoboTaskPamaldReport(): void
    {
        $actual = $this->runRoboCommand(['pamald:report']);
        $expected = [
            'exitCode' => 0,
            'out' => <<< 'Text'
                +------+-----------+-----------+----------------+----------------+---------+---------+
                | Name | L Version | R Version | L Relationship | R Relationship | L Depth | R Depth |
                +------+-----------+-----------+----------------+----------------+---------+---------+
                | a/b  | 2.0.0     | 2.1.3     | prod           | prod           | direct  | direct  |
                | a/c  |           | 5.0.0     |                | prod           |         | direct  |
                | a/d  |           | 6.0.0     |                | prod           |         | child   |
                +------+-----------+-----------+----------------+----------------+---------+---------+

                Text,
            'err' => implode(
                "\n",
                [
                    ' [pamald - Collect Composer packages] Collect Composer packages',
                    ' [pamald - Collect Composer packages] Collect Composer packages',
                    ' [Pamald\Robo\Pamald\Task\LockDifferTask] ',
                    ' [Pamald\Robo\Pamald\Task\ReporterTask] ',
                    '',
                ],
            ),
        ];

        static::assertSame($expected['out'], $actual['out'], 'stdOutput');
        static::assertSame($expected['err'], $actual['err'], 'stdError');
        static::assertSame($expected['exitCode'], $actual['exitCode'], 'exitCode');
    }
}
