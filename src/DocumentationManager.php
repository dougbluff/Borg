<?php

namespace Behat\Borg;

use Behat\Borg\Documentation\Processor\Processor;
use Behat\Borg\Documentation\RawDocumentation;
use Behat\Borg\Documentation\DocumentationId;
use Behat\Borg\Documentation\Page\PageId;
use Behat\Borg\Documentation\Page\Page;
use Behat\Borg\Documentation\Publisher\PublishedDocumentation;
use Behat\Borg\Documentation\Repository\Repository;

/**
 * Manages documentation by providing high-level accessor methods.
 */
final class DocumentationManager
{
    /**
     * @var Processor
     */
    private $processor;
    /**
     * @var Repository
     */
    private $repository;

    /**
     * Initialize manager.
     *
     * @param Processor  $processor
     * @param Repository $repository
     */
    public function __construct(Processor $processor, Repository $repository)
    {
        $this->processor = $processor;
        $this->repository = $repository;
    }

    /**
     * Processes raw documentation.
     *
     * @param RawDocumentation $documentation
     */
    public function process(RawDocumentation $documentation)
    {
        $this->processor->process($documentation);
    }

    /**
     * Tries to find the documentation page using its ID.
     *
     * @param DocumentationId $documentationId
     * @param PageId          $pageId
     *
     * @return Page|null
     */
    public function findPage(DocumentationId $documentationId, PageId $pageId)
    {
        if (!$documentation = $this->repository->find($documentationId)) {
            return null;
        }

        if (!$documentation->hasPage($pageId)) {
            return null;
        }

        return $documentation->getPage($pageId);
    }

    /**
     * Tries to find all available documentation for provided project name.
     *
     * @param string $projectName
     *
     * @return PublishedDocumentation[]
     */
    public function getAvailableDocumentation($projectName)
    {
        return $this->repository->findForProject($projectName);
    }
}
