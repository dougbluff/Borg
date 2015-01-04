<?php

namespace spec\Behat\Borg\GitHub;

use Behat\Borg\GitHub\Commit;
use Behat\Borg\GitHub\GitHubRepository;
use Behat\Borg\Release\Downloader\Download;
use Behat\Borg\Release\Release;
use Behat\Borg\Release\Version;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GitHubDownloadSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(
            new Release(
                GitHubRepository::named('behat/docs'),
                Version::string('v2.5')
            ),
            Commit::committedWithShaAtTime(
                '839e5185da9434753db47959bee16642bb4f2ce4',
                new \DateTimeImmutable('2011-04-14T16:00:49Z')
            ),
            __DIR__
        );
    }

    function it_is_a_download()
    {
        $this->shouldHaveType(Download::class);
    }

    function it_holds_a_release()
    {
        $this->getRelease()->shouldBeLike(
            new Release(
                GitHubRepository::named('behat/docs'),
                Version::string('v2.5')
            )
        );
    }

    function it_holds_a_commit()
    {
        $this->getCommit()->shouldBeLike(
            Commit::committedWithShaAtTime(
                '839e5185da9434753db47959bee16642bb4f2ce4',
                new \DateTimeImmutable('2011-04-14T16:00:49Z')
            )
        );
    }

    function its_release_time_is_a_time_of_commit()
    {
        $this->getReleaseTime()->shouldBeLike(new \DateTimeImmutable('2011-04-14T16:00:49Z'));
    }

    function its_path_is_a_path_it_was_constructed_with()
    {
        $this->getPath()->shouldReturn(__DIR__);
    }

    function it_could_say_if_file_is_included_in_release()
    {
        $this->shouldHaveFile(basename(__FILE__));
        $this->shouldNotHaveFile('any_file');
    }
}
