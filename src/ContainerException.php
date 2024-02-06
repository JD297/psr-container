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
use RuntimeException;

class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
    public const ERROR_MESSAGE_IS_NOT_INSTANTIABLE = 'Id: "%s" is not instantiable.';
    public const ERROR_MESSAGE_REFLECTION_PARAMETER_TYPE_NOT_OF_TYPE_REFLECTION_NAMED_TYPE = 'Id: "%s" reflection parameter type expected to be of type "ReflectionNamedType".';
    public const ERROR_MESSAGE_BUILT_IN_TYPE_WITH_NAME_CAN_NOT_BE_RESOLVED = 'Id: "%s" built in type with name "%s" can not be resolved.';
    public const ERROR_MESSAGE_EMPTY_ID_IS_NOT_SUPPORTED = 'An empty id is not support.';
    public const ERROR_MESSAGE_ADDING_CONTAINER_NOT_IT_SELF_IS_NOT_ALLOWED = 'Adding the container to it self is not allowed.';
    public const ERROR_MESSAGE_CALLABLE_SHOULD_NOT_RETURN_VOID = 'The provided callable must not return void.';
    public const ERROR_MESSAGE_CLASS_WITH_ID_DOES_NOT_EXIST = 'Id: "%s" the class does not exist';
}
