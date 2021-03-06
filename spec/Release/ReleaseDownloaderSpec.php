<?php

namespace spec\Behat\Borg\Release;

use Behat\Borg\Release\Downloader\Download;
use Behat\Borg\Release\Downloader\Downloader;
use Behat\Borg\Release\Listener\DownloadListener;
use Behat\Borg\Release\Listener\ReleaseListener;
use Behat\Borg\Release\Repository;
use Behat\Borg\Release\Release;
use Behat\Borg\Release\Version;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseDownloaderSpec extends ObjectBehavior
{
    function let(Downloader $downloader)
    {
        $this->beConstructedWith($downloader);
    }

    function it_is_a_release_listener()
    {
        $this->shouldHaveType(ReleaseListener::class);
    }

    function it_downloads_every_new_release_and_notifies_registered_listeners(
        Repository $repository,
        Downloader $downloader,
        Download $downloadedRelease,
        DownloadListener $listener1,
        DownloadListener $listener2
    ) {
        $release = new Release($repository->getWrappedObject(), Version::string('v2.5'));
        $downloader->download($release)->willReturn($downloadedRelease);

        $listener1->releaseDownloaded($downloadedRelease)->shouldBeCalled();
        $listener2->releaseDownloaded($downloadedRelease)->shouldBeCalled();

        $this->registerListener($listener1);
        $this->registerListener($listener2);

        $this->releaseReleased($release);
    }
}
