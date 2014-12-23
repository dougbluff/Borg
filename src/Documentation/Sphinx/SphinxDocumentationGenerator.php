<?php

namespace Behat\Borg\Documentation\Sphinx;

use Behat\Borg\Documentation\Builder\Generator\DocumentationGenerator;
use Behat\Borg\Documentation\Documentation;
use Behat\Borg\Documentation\DocumentationId;
use DateTimeImmutable;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * Generates *.rst documentation using Sphinx.
 *
 * @see http://sphinx-doc.org
 */
final class SphinxDocumentationGenerator implements DocumentationGenerator
{
    const COMMAND_LINE = 'sphinx-build';

    private $buildPath;
    private $configPath;
    private $filesystem;

    /**
     * @param string     $buildPath
     * @param string     $configPath
     * @param Filesystem $filesystem
     */
    public function __construct($buildPath, $configPath, Filesystem $filesystem)
    {
        $this->buildPath = $buildPath;
        $this->configPath = $configPath;
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(Documentation $documentation)
    {
        $source = $documentation->getSource();

        if (!$source instanceof RstDocumentationSource) {
            return null;
        }

        $sourcePath = $source->getPath();
        $buildPath = $this->getWritableBuildPath($documentation);
        $commandLine = $this->getCommandLine($documentation->getId(), $sourcePath, $buildPath);

        $this->executeCommand($commandLine);

        return new BuiltSphinxDocumentation(
            $documentation->getId(), $documentation->getTime(), new DateTimeImmutable(), $buildPath
        );
    }

    private function getWritableBuildPath(Documentation $documentation)
    {
        $buildPath = $this->buildPath . '/' . $documentation->getId();
        $this->filesystem->mkdir($buildPath);

        return $buildPath;
    }

    private function getCommandLine(DocumentationId $anId, $sourcePath, $buildPath)
    {
        return sprintf(
            '%s -b html -c %s -D project="%s" -D version="%s" -D release="%s" %s %s',
            self::COMMAND_LINE,
            $this->configPath,
            $anId->getProjectName(),
            $anId->getVersionString(),
            $anId->getVersionString(),
            $sourcePath,
            $buildPath
        );
    }

    private function executeCommand($commandLine)
    {
        $process = new Process(self::COMMAND_LINE);

        $process->setCommandLine($commandLine);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException(
                sprintf(
                    "%s\n%s",
                    $process->getOutput(),
                    $process->getErrorOutput()
                )
            );
        }
    }
}
