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
            // phpcs:disable Generic.Files.LineLength.TooLong
            'out' => <<< 'Text'
                +------+-----------+-----------+---------+---------+----------+----------+------------+------------+---------+---------+
                | Name | L Version | R Version | L Type  | R Type  | L Link   | R Link   | L Env      | R Env      | L Depth | R Depth |
                +------+-----------+-----------+---------+---------+----------+----------+------------+------------+---------+---------+
                | Production - Direct                                                                                                  |
                | a/b  | 2.0.0     | 2.1.3     | package | package | required | required | production | production | direct  | direct  |
                | a/c  |           | 5.0.0     |         | package |          | required |            | production |         | direct  |
                | Production - Indirect                                                                                                |
                | a/d  |           | 6.0.0     |         | package |          | required |            | production |         | child   |
                +------+-----------+-----------+---------+---------+----------+----------+------------+------------+---------+---------+

                Text,
            // phpcs:enable Generic.Files.LineLength.TooLong
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
