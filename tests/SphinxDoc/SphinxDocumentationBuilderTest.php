<?php

namespace tests\Behat\Borg\SphinxDoc;

use Behat\Borg\Documentation\Documentation;
use Behat\Borg\Documentation\DocumentationId;
use Behat\Borg\Documentation\DocumentationSource;
use Behat\Borg\Package\Downloader\DownloadedRelease;
use Behat\Borg\Package\Package;
use Behat\Borg\Package\Release;
use Behat\Borg\Package\Version;
use Behat\Borg\SphinxDoc\RstDocumentationSource;
use Behat\Borg\SphinxDoc\SphinxDocumentationBuilder;
use DateTimeImmutable;
use PHPUnit_Framework_TestCase;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class SphinxDocumentationBuilderTest extends PHPUnit_Framework_TestCase
{
    private $tempInputPath;
    private $tempOutputPath;
    private $builder;

    protected function setUp()
    {
        $this->tempInputPath = getenv('TEMP_PATH') . '/sphinx/input';
        $this->tempOutputPath = getenv('TEMP_PATH') . '/sphinx/output';

        $this->builder = new SphinxDocumentationBuilder(
            $this->tempOutputPath, realpath(__DIR__ . '/../../sphinx'), new Filesystem()
        );

        (new Filesystem())->remove([$this->tempInputPath, $this->tempOutputPath]);
    }

    /** @test @expectedException InvalidArgumentException */
    function it_throws_an_exception_if_non_RST_documentation_provided()
    {
        $download = $this->createDownload('my/doc', '1.3.5');
        $source = $this->createDocumentationSource();
        $documentation = Documentation::downloaded($download, $source);

        $this->builder->buildDocumentation($documentation);
    }

    /** @test */
    function it_builds_RST_documentation_into_the_output_path()
    {
        $download = $this->createDownload('my/doc', 'v1.3.5');
        $source = $this->createRstDocumentationSourceWithIndex("Docs\n====");
        $documentation = Documentation::downloaded($download, $source);

        $built = $this->builder->buildDocumentation($documentation);

        $this->assertFileExists($this->tempOutputPath . '/my/doc/v1.3.5/index.html');
        $this->assertEquals($this->tempOutputPath . '/my/doc/v1.3.5/index.html', $built->getIndexPath());

        $this->assertContains('<h1>Docs', file_get_contents($built->getIndexPath()));
        $this->assertContains('my/doc', file_get_contents($built->getIndexPath()));
        $this->assertContains('v1.3.5', file_get_contents($built->getIndexPath()));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    function it_throws_an_exception_if_sphinx_can_not_build_documents()
    {
        $download = $this->createDownload('my/doc', '1.3.5');
        $source = $this->createRstDocumentationSourceWithoutIndex();
        $documentation = Documentation::downloaded($download, $source);

        $this->builder->buildDocumentation($documentation);
    }

    /**
     * @param string $packageName
     * @param string $version
     *
     * @return DownloadedRelease
     */
    private function createDownload($packageName, $version)
    {
        $package = $this->getMock(Package::class);
        $package->method('__toString')->willReturn($packageName);

        $download = $this->getMock(DownloadedRelease::class);
        $download->method('getRelease')->willReturn(
            $release = new Release($package, Version::string($version))
        );
        $download->method('getReleaseTime')->willReturn(new DateTimeImmutable());

        return $download;
    }

    /**
     * @return DocumentationSource
     */
    private function createDocumentationSource()
    {
        return $this->getMock(DocumentationSource::class);
    }

    /**
     * @param string $content
     *
     * @return RstDocumentationSource
     */
    private function createRstDocumentationSourceWithIndex($content)
    {
        (new Filesystem())->dumpFile($this->tempInputPath . '/index.rst', $content);

        return RstDocumentationSource::atPath($this->tempInputPath);
    }

    /**
     * @return RstDocumentationSource
     */
    private function createRstDocumentationSourceWithoutIndex()
    {
        return RstDocumentationSource::atPath($this->tempInputPath);
    }
}
