<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test;

use Jd297\Psr\Container\ContainerException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

class ContainerExceptionTest extends TestCase
{
    public function testContainerExceptionImplementsPsrContainerExceptionInterface(): void
    {
        $this->assertInstanceOf(ContainerExceptionInterface::class, new ContainerException());
    }
}
