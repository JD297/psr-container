<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionNamedType;

class Container implements ContainerInterface
{
    /**
     * @var array<string, mixed> $entries
     */
    protected array $entries;

    /**
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        if ($this->isSelf($id)) {
            return $this;
        }

        if (!$this->has($id)) {
            throw new NotFoundException(sprintf(NotFoundException::ERROR_MESSAGE_ID_NOT_FOUND, $id));
        }

        return $this->entries[$id];
    }

    public function has(string $id): bool
    {
        if (isset($this->entries[$id]) || $this->isSelf($id)) {
            return true;
        }

        return false;
    }

    /**
     * @throws ContainerExceptionInterface
     * @return $this
     */
    public function add(string $id, ?callable $instantiate = null): self
    {
        if (empty($id)) {
            throw new ContainerException(ContainerException::ERROR_MESSAGE_EMPTY_ID_IS_NOT_SUPPORTED);
        }

        if ($this->isSelf($id)) {
            throw new ContainerException(ContainerException::ERROR_MESSAGE_ADDING_CONTAINER_NOT_IT_SELF_IS_NOT_ALLOWED);
        }

        if (!is_callable($instantiate)) {
            return $this->wire($id);
        }

        if (!$this->entries[$id] = $instantiate($this)) {
            throw new ContainerException(ContainerException::ERROR_MESSAGE_CALLABLE_SHOULD_NOT_RETURN_VOID);
        }

        return $this;
    }

    /**
     * @throws ContainerExceptionInterface
     * @return $this
     */
    private function wire(string $id): self
    {
        if (!class_exists($id)) {
            throw new ContainerException(sprintf(ContainerException::ERROR_MESSAGE_CLASS_WITH_ID_DOES_NOT_EXIST, $id));
        }

        $reflector = new ReflectionClass($id);

        if (!$reflector->isInstantiable()) {
            throw new ContainerException(sprintf(ContainerException::ERROR_MESSAGE_IS_NOT_INSTANTIABLE, $id));
        }

        if (!$constructor = $reflector->getConstructor()) {
            return $this->add($id, function () use ($id) {
                return new $id();
            });
        }

        if (!$parameters = $constructor->getParameters()) {
            $this->add($id, function () use ($id) {
                return new $id();
            });
        }

        $constructorArguments = [];

        foreach ($parameters as $parameter) {
            if (!$parameter->getType() instanceof ReflectionNamedType) {
                throw new ContainerException(sprintf(ContainerException::ERROR_MESSAGE_REFLECTION_PARAMETER_TYPE_NOT_OF_TYPE_REFLECTION_NAMED_TYPE, $id));
            }

            if ($parameter->getType()->isBuiltin()) {
                $itemId = $parameter->getName();
            } else {
                $itemId = $parameter->getType()->getName();
            }

            $has = $this->has($itemId);

            if ($parameter->getType()->isBuiltin() && !$has) {
                throw new ContainerException(sprintf(ContainerException::ERROR_MESSAGE_BUILT_IN_TYPE_WITH_NAME_CAN_NOT_BE_RESOLVED, $id, $itemId));
            } elseif (!$has) {
                $this->add($itemId);
            }

            $constructorArguments[] = $this->get($itemId);
        }

        return $this->add($id, function () use ($id, $constructorArguments) {
            return new $id(...$constructorArguments);
        });
    }

    protected function isSelf(string $id): bool
    {
        return $id === Container::class;
    }
}
