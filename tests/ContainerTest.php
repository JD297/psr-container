<?php

/**
 * (c) Jan Dommasch <jan.dommasch297@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jd297\Psr\Container\Test;

use Jd297\Psr\Container\Container;
use Jd297\Psr\Container\ContainerException;
use Jd297\Psr\Container\NotFoundException;
use Jd297\Psr\Container\Test\Asset\BuiltInTypeDependencyAsset;
use Jd297\Psr\Container\Test\Asset\DependencyAsset;
use Jd297\Psr\Container\Test\Asset\DependencyIntersectionAsset;
use Jd297\Psr\Container\Test\Asset\EnvironmentVariableDependencyAsset;
use Jd297\Psr\Container\Test\Asset\IndependentAsset;
use Jd297\Psr\Container\Test\Asset\IndependentEmptyConstrutorAsset;
use Jd297\Psr\Container\Test\Asset\IndependentPrivateConstrutorAsset;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ContainerTest extends TestCase
{
    public function testContainerImplementsPsrContainerInterface(): void
    {
        $this->assertInstanceOf(ContainerInterface::class, new Container());
    }

    public function testContainerHasMethodClassDoesNotExist(): void
    {
        $container = new Container();
        $this->assertFalse($container->has('Does\\Not\\Exist'));
    }

    public function testContainerHasMethodWithItSelf(): void
    {
        $container = new Container();
        $this->assertTrue($container->has(Container::class));
    }

    public function testContainerGetMethodThrowsNotFoundExceptionInterface(): void
    {
        $container = new Container();

        $id = 'Does\\Not\\Exist';

        $this->expectException(NotFoundExceptionInterface::class);
        $this->expectExceptionMessage(sprintf(NotFoundException::ERROR_MESSAGE_ID_NOT_FOUND, $id));

        $container->get($id);
    }

    public function testContainerGetMethodWithItSelf(): void
    {
        $container = new Container();

        $retrieved = $container->get(Container::class);

        $this->assertInstanceOf(Container::class, $retrieved);
        $this->assertSame($container, $retrieved);
    }

    public function testContainerAddMethodReturnsSelf(): void
    {
        $container = new Container();

        $this->assertSame($container, $container->add('env', function () {
            return 'dev';
        }));
    }

    public function testContainerAddMethodWithItSelfThrowsContainerExceptionInterface(): void
    {
        $container = new Container();

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(ContainerException::ERROR_MESSAGE_ADDING_CONTAINER_NOT_IT_SELF_IS_NOT_ALLOWED);

        $container->add(Container::class, function () {
            return new Container();
        });

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(ContainerException::ERROR_MESSAGE_ADDING_CONTAINER_NOT_IT_SELF_IS_NOT_ALLOWED);

        $container->add(Container::class);
    }

    public function testContainerAddMethodWithBasicValues(): void
    {
        $container = new Container();

        $env = 'dev';
        $clients = 64;

        $container
            ->add('env', function () use ($env) {
                return $env;
            })
            ->add('clients', function () use ($clients) {
                return $clients;
            });

        $this->assertEquals($env, $container->get('env'));
        $this->assertEquals($clients, $container->get('clients'));
    }

    public function testContainerAddMethodWithEmptyId(): void
    {
        $container = new Container();

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectErrorMessage(ContainerException::ERROR_MESSAGE_EMPTY_ID_IS_NOT_SUPPORTED);

        $container->add('', function () {
            return 'value';
        });
    }

    public function testContainerAddMethodWithVoidCallable(): void
    {
        $container = new Container();

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectErrorMessage(ContainerException::ERROR_MESSAGE_CALLABLE_SHOULD_NOT_RETURN_VOID);

        $container->add('env', function () {
        });
    }

    public function testContainerAddMethodWithDependencyClass(): void
    {
        $container = new Container();

        $container->add(DependencyAsset::class);

        $this->assertTrue($container->has(DependencyAsset::class));
        $this->assertTrue($container->has(IndependentAsset::class));

        $dependencyAsset = $container->get(DependencyAsset::class);

        $this->assertInstanceOf(DependencyAsset::class, $dependencyAsset);
        $this->assertInstanceOf(IndependentAsset::class, $dependencyAsset->getIndependentAsset());
    }

    public function testContainerAddMethodWithEnvironmentVariableDependencyClass(): void
    {
        $container = new Container();

        $env = 'dev';

        $container->add('env', function () use ($env) {
            return $env;
        });
        $container->add(EnvironmentVariableDependencyAsset::class);

        /** @var EnvironmentVariableDependencyAsset $environmentVariableDependencyAsset */
        $environmentVariableDependencyAsset = $container->get(EnvironmentVariableDependencyAsset::class);

        $this->assertInstanceOf(EnvironmentVariableDependencyAsset::class, $environmentVariableDependencyAsset);
        $this->assertEquals($env, $environmentVariableDependencyAsset->getEnv());
    }

    public function testContainerAddMethodClassDoesNotExist(): void
    {
        $container = new Container();

        $id = 'Does\\Not\\Exist';

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(sprintf(ContainerException::ERROR_MESSAGE_CLASS_WITH_ID_DOES_NOT_EXIST, $id));

        $container->add($id);
    }

    public function testContainerAddMethodWithEmptyConstructor(): void
    {
        $container = new Container();

        $container->add(IndependentEmptyConstrutorAsset::class);

        $this->assertInstanceOf(IndependentEmptyConstrutorAsset::class, $container->get(IndependentEmptyConstrutorAsset::class));
    }

    public function testContainerAddMethodWithPrivateConstructor(): void
    {
        $container = new Container();

        $id = IndependentPrivateConstrutorAsset::class;

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(sprintf(ContainerException::ERROR_MESSAGE_IS_NOT_INSTANTIABLE, $id));

        $container->add($id);
    }

    public function testContainerAddMethodWithBuiltInTypeThatCanNotBeResolved(): void
    {
        $container = new Container();

        $id = BuiltInTypeDependencyAsset::class;

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(sprintf(ContainerException::ERROR_MESSAGE_BUILT_IN_TYPE_WITH_NAME_CAN_NOT_BE_RESOLVED, $id, 'builtIn'));

        $container->add($id);
    }

    public function testContainerAddMethodWithIntersectionDependencyType(): void
    {
        $container = new Container();

        $id = DependencyIntersectionAsset::class;

        $this->expectException(ContainerExceptionInterface::class);
        $this->expectExceptionMessage(sprintf(ContainerException::ERROR_MESSAGE_REFLECTION_PARAMETER_TYPE_NOT_OF_TYPE_REFLECTION_NAMED_TYPE, $id));

        $container->add($id);
    }
}
