# psr-container (PSR-11)

Simple implementation of [PSR-11 (Container Interface)](https://www.php-fig.org/psr/psr-11/).

## Requirements

The following versions of PHP are supported by this version.

* PHP ^8.1

## Usage

```php
use Acme\Service\ExampleService;
use Jd297\Psr\Clock\SystemClock;
use Jd297\Psr\Container\Container;
use Jd297\Psr\Logger\Handler\FileHandler;
use Jd297\Psr\Logger\Logger;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;

$container = new Container();

$container->add('projectDir', fn () => __DIR__.'/..');

$container->add(ClockInterface::class, fn () => new SystemClock());

$container->add(LoggerInterface::class, function (Container $container) {
    return new Logger(
        $container->get(ClockInterface::class),
        [
            new FileHandler(sprintf('%s/var/log/dev.log', $container->get('projectDir')))
        ]
    );
});

/** @var ExampleService $exampleService */
$exampleService = $container->add(ExampleService::class)->get(ExampleService::class);
$exampleService->setLogger($container->get(LoggerInterface::class)); // using the LoggerAwareTrait

$exampleService->execute();
```

## Composer

### Scripts

Reformat code with [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer).
``` bash
$ composer reformat
```

Test source code with [PHPUnit](https://github.com/sebastianbergmann/phpunit).
``` bash
$ composer unit
```

Analyse files with [PHPStan](https://github.com/phpstan/phpstan) (Level 9).
``` bash
$ composer analyse
```

## License

The BSD 2-Clause "Simplified" License (BSD-2-Clause). Please see [License File](https://github.com/jd297/psr-container/blob/master/LICENSE) for more information.
