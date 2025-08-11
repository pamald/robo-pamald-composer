<?php

declare(strict_types=1);

use Consolidation\AnnotatedCommand\Attributes\Command;
use Pamald\Pamald\Reporter\ConsoleTableReporter;
use Pamald\PamaldComposer\DependencyCollector;
use Pamald\Robo\Pamald\PamaldTaskLoader;
use Pamald\Robo\PamaldComposer\PamaldComposerTaskLoader;
use Robo\Tasks;
use Robo\Contract\TaskInterface;
use Robo\State\Data as RoboState;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\Yaml\Yaml;

class AcceptanceRoboFile extends Tasks
{
    use PamaldTaskLoader;
    use PamaldComposerTaskLoader;

    protected function output()
    {
        return $this->getContainer()->get('output');
    }

    #[Command(name: 'pamald:report')]
    public function cmdPamaldReportExecute(): TaskInterface
    {
        $cb = $this->collectionBuilder();
        $cb
            ->addCode(function (RoboState $state): int {
                $projectDir = $this->fixturesDir('project-01');
                $state['phase-01.json'] = json_decode(
                    file_get_contents("$projectDir/phase-01.base.json") ?: '{}',
                    true,
                );
                $state['phase-01.lock'] = json_decode(
                    file_get_contents("$projectDir/phase-01.lock.json") ?: '{}',
                    true,
                );
                $state['phase-02.json'] = json_decode(
                    file_get_contents("$projectDir/phase-02.base.json") ?: '{}',
                    true,
                );
                $state['phase-02.lock'] = json_decode(
                    file_get_contents("$projectDir/phase-02.lock.json") ?: '{}',
                    true,
                );

                $state['collector'] = new DependencyCollector();

                $reporter = new ConsoleTableReporter();
                $reporter->setTable(new Table($this->output()));
                $state['reporter'] = $reporter;

                return 0;
            })
            ->addTask(
                $this
                    ->taskPamaldCollectComposerPackages()
                    ->setAssetNamePrefix('left.')
                    ->deferTaskConfiguration('setCollector', 'collector')
                    ->deferTaskConfiguration('setLock', 'phase-01.lock')
                    ->deferTaskConfiguration('setJson', 'phase-01.json')
            )
            ->addTask(
                $this
                    ->taskPamaldCollectComposerPackages()
                    ->setAssetNamePrefix('right.')
                    ->deferTaskConfiguration('setCollector', 'collector')
                    ->deferTaskConfiguration('setLock', 'phase-02.lock')
                    ->deferTaskConfiguration('setJson', 'phase-02.json')
            )
            ->addTask(
                $this
                    ->taskPamaldLockDiffer()
                    ->deferTaskConfiguration('setLeftPackages', 'left.pamald.composer.dependencies')
                    ->deferTaskConfiguration('setRightPackages', 'right.pamald.composer.dependencies')
            )
            ->addTask(
                $this
                    ->taskPamaldReporter()
                    ->deferTaskConfiguration('setLockDiffEntries', 'pamald.lockDiffEntries')
                    ->deferTaskConfiguration('setReporter', 'reporter')
            );

        return $cb;
    }

    #[Command(name: 'pamald:git-hook:prepare-commit-msg')]
    public function cmdGitHookPrepareCommitMsgExecute(): TaskInterface
    {
        $cb = $this->collectionBuilder();

        $cb->addCode(function (RoboState $state): int {
            $state['commitMsgParts'] = [
                //
            ];

            return 0;
        });

        $taskModify = $this->taskPamaldComposerModifyCommitMsgParts();
        $taskModify->setStateKeyCommitMsgParts('commitMsgParts');
        $cb->addTask($taskModify);

        $cb->addCode(function (RoboState $state): int {
            $this->output()->writeln(Yaml::dump($state['commitMsgParts'], 99, 4));

            return 0;
        });

        return $cb;
    }

    protected function selfRoot(): string
    {
        return dirname(__DIR__);
    }

    protected function fixturesDir(string ...$parts): string
    {
        return Path::join(
            $this->selfRoot(),
            'tests',
            'fixtures',
            ...$parts,
        );
    }
}
