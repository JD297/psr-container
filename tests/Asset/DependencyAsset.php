<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test\Asset;

class DependencyAsset
{
    private IndependentAsset $independentAsset;

    public function __construct(IndependentAsset $independentAsset)
    {
        $this->independentAsset = $independentAsset;
    }

    public function getIndependentAsset(): IndependentAsset
    {
        return $this->independentAsset;
    }
}
