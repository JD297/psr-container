<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test\Asset;

class EnvironmentVariableDependencyAsset
{
    private string $env;

    public function __construct(string $env)
    {
        $this->setEnv($env);
    }

    public function getEnv(): string
    {
        return $this->env;
    }


    public function setEnv(string $env): void
    {
        $this->env = $env;
    }
}
