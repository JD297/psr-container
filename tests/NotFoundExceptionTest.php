<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test;

use Jd297\Psr\Container\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundExceptionTest extends TestCase
{
    public function testNotFoundExceptionImplementsPsrNotFoundExceptionInterface(): void
    {
        $this->assertInstanceOf(NotFoundExceptionInterface::class, new NotFoundException());
    }
}
