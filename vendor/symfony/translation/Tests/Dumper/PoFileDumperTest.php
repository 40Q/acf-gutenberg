<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Translation\Tests\Dumper;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Translation\Dumper\PoFileDumper;
use Symfony\Component\Translation\MessageCatalogue;

class PoFileDumperTest extends TestCase
{
    public function testFormatCatalogue()
    {
        $catalogue = new MessageCatalogue('en');
<<<<<<< HEAD
        $catalogue->add(['foo' => 'bar']);
=======
        $catalogue->add(['foo' => 'bar', 'bar' => 'foo']);
>>>>>>> 69be027aa2a83077bb69d988d56c10a8a0c75d22

        $dumper = new PoFileDumper();

        $this->assertStringEqualsFile(__DIR__.'/../fixtures/resources.po', $dumper->formatCatalogue($catalogue, 'messages'));
    }
}
