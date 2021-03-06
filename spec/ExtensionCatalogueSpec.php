<?php

namespace spec\Behat\Borg;

use Behat\Borg\Extension\Extension;
use Behat\Borg\Extension\Repository\Repository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExtensionCatalogueSpec extends ObjectBehavior
{
    function let(Repository $repository)
    {
        $this->beConstructedWith($repository);
    }

    function it_finds_registered_extensions_using_repository(Repository $repository, Extension $extension)
    {
        $repository->extension('some/extension')->willReturn($extension);

        $this->extension('some/extension')->shouldReturn($extension);
    }

    function it_finds_all_registered_extensions_using_repository(Repository $repository, Extension $extension)
    {
        $repository->all()->willReturn([$extension]);

        $this->all()->shouldReturn([$extension]);
    }
}
