<?php

namespace spec\Behat\Borg\Package;

use Behat\Borg\Package\Package;
use Behat\Borg\Package\Version;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ReleaseSpec extends ObjectBehavior
{
    function let(Package $package)
    {
        $package->__toString()->willReturn('my_package');
    }

    function it_is_a_combination_of_package_and_specific_version(Package $package)
    {
        $version = Version::string('1.0.0');
        $this->beConstructedWith($package, $version);

        $this->getPackage()->shouldReturn($package);
        $this->getVersion()->shouldReturn($version);
    }

    function it_can_be_represented_as_a_string(Package $package)
    {
        $this->beConstructedWith($package, Version::string('1.0.0'));

        $this->__toString()->shouldReturn('my_package/1.0.0');
    }
}
