<?php

namespace spec\Behat\Borg\Documentation;

use Behat\Borg\Documentation\RawDocumentation;
use Behat\Borg\Documentation\DocumentationId;
use Behat\Borg\Documentation\Source;
use Behat\Borg\Release\Downloader\Download;
use Behat\Borg\Release\Repository;
use Behat\Borg\Release\Release;
use Behat\Borg\Release\Version;
use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RawDocumentationSpec extends ObjectBehavior
{
    function let(DateTimeImmutable $time, Source $source)
    {
        $this->beConstructedWith(new DocumentationId('behat/behat', 'v1.0'), $time, $source);
    }

    function it_has_an_id()
    {
        $this->documentationId()->shouldBeLike(new DocumentationId('behat/behat', 'v1.0'));
    }

    function it_has_a_source(Source $source)
    {
        $this->source()->shouldReturn($source);
    }

    function it_holds_a_time_it_was_created_at(DateTimeImmutable $time)
    {
        $this->documentedAt()->shouldReturn($time);
    }
}
