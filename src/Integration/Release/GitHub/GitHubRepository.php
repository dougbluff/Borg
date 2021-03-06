<?php

namespace Behat\Borg\Integration\Release\GitHub;

use Behat\Borg\Integration\Release\GitHub\Exception\BadRepositoryNameGiven;
use Behat\Borg\Release\Repository;

/**
 * Represents a GitHub package.
 */
final class GitHubRepository implements Repository
{
    private $repositoryString;
    private $organisation;
    private $repository;

    /**
     * Initializes GitHub package.
     *
     * @param $repositoryString
     *
     * @return GitHubRepository
     *
     * @throws BadRepositoryNameGiven
     */
    public static function named($repositoryString)
    {
        if (!preg_match('/^[\w\-\.]++\/[\w\-\.]++$/', $repositoryString)) {
            throw new BadRepositoryNameGiven(
                "`{$repositoryString}` is not a supported GitHub repository name."
            );
        }

        $package = new GitHubRepository();
        $package->repositoryString = $repositoryString;
        list($package->organisation, $package->repository) = explode('/', $repositoryString);

        return $package;
    }

    /**
     * {@inheritdoc}
     */
    public function organisationName()
    {
        return $this->organisation;
    }

    /**
     * {@inheritdoc}
     */
    public function name()
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->repositoryString;
    }

    private function __construct() { }
}
