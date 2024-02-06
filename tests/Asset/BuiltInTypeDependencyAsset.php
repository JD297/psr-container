<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test\Asset;

class BuiltInTypeDependencyAsset
{
    private string $builtIn;

    public function __construct(string $builtIn)
    {
        $this->setBuiltIn($builtIn);
    }

    public function getBuiltIn(): string
    {
        return $this->builtIn;
    }


    public function setBuiltIn(string $builtIn): void
    {
        $this->builtIn = $builtIn;
    }
}
