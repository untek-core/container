<?php

namespace Untek\Core\Container\Libs\BundleLoaders;

use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\ParameterGenerator;
use Laminas\Code\Generator\ValueGenerator;
use Opis\Closure\ReflectionClosure;
use Opis\Closure\SerializableClosure;
use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Core\Code\Helpers\ClosureHelper;
use Untek\Core\Container\Interfaces\ContainerConfiguratorInterface;
use Untek\Core\Container\Libs\ContainerConfigurators\ArrayContainerConfigurator;
use Untek\Core\Instance\Libs\Resolvers\InstanceResolver;
use Untek\Core\Instance\Libs\Resolvers\MethodParametersResolver;

class Container1 extends \Untek\Core\Container\Libs\Container
{


    public function __construct()
    {
        $this->bindings = [];
    }
}

/**
 * Загрузчик конфигурации контейнера
 */
class ContainerLoader extends BaseLoader
{

    private $enableCache = false;

    public function loadAll(array $bundles): void
    {
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            foreach ($containerConfigList as $containerConfig) {
                $this->importFromConfig($containerConfig);
            }
        }
    }

//    public function loadAll222222(array $bundles): void
//    {
//        $cacheFile = getenv('VAR_DIRECTORY') . '/ddd.php';
//        if ($this->enableCache && file_exists($cacheFile)) {
//            $requiredConfig = require $cacheFile;
//            $this->loadFromArray($requiredConfig);
//        } else {
//            foreach ($bundles as $bundle) {
//                $containerConfigList = $this->load($bundle);
//                foreach ($containerConfigList as $containerConfig) {
//                    $this->importFromConfig($containerConfig);
//                }
//            }
//            if ($this->enableCache) {
//                file_put_contents(
//                    $cacheFile,
//                    "<?php return [
//                        'singletons' => [
//                            {$this->compiledSingletons}
//                        ],
//                        'definitions' => [
//                            {$this->compiledDefinitions}
//                        ],
//                    ];"
//                );
//            }
//        }
//        /*$cc = '';
//        foreach ($this->container->getBindings() as $abstract => $vv) {
//            $concreteCode = $this->genVal3333($vv['concrete']);
//            $abstractValueGenerator = new ValueGenerator();
//            $abstractValueGenerator->setValue($abstract);
//            $rr = $abstractValueGenerator->generate();
//            $cc .= "
//            {$rr} =>
//            [
//                'concrete' => $concreteCode,
//                'shared' => ".($vv['shared'] ? 'true' : 'false').",
//            ],
//            ";
//        }
//        dd($cc);*/
//    }

    private function importFromConfig($configFile): void
    {
        $requiredConfig = require($configFile);
        if (is_array($requiredConfig)) {
            $this->loadFromArray($requiredConfig);
        } elseif (is_callable($requiredConfig)) {
            $this->loadFromCallback($requiredConfig);
        }
    }

    private $compiledSingletons = '';
    private $compiledDefinitions = '';

    private function genVal3333($concrete)
    {
        if (is_callable($concrete)) {
            $closureCode = ClosureHelper::serialize($concrete);
            $code = $closureCode;
        } else {
            $vg = new ValueGenerator();
            $vg->setValue($concrete);
            $code = $vg->generate();
        }
        return $code;
    }

    private function genVal($abstract, $concrete)
    {
        $code = $this->genVal3333($concrete);
        $abstractValueGenerator = new ValueGenerator();
        $abstractValueGenerator->setValue($abstract);
        $rr = $abstractValueGenerator->generate();
        return "{$rr} => $code,\n";
    }

    private function loadFromArray(array $requiredConfig): void
    {
        /** @var ContainerConfiguratorInterface $containerConfigurator */
        $containerConfigurator = $this
            ->getContainer()
            ->get(ContainerConfiguratorInterface::class);
        if (!empty($requiredConfig['singletons'])) {
            foreach ($requiredConfig['singletons'] as $abstract => $concrete) {
                if ($this->enableCache) {
                    $this->compiledSingletons .= $this->genVal($abstract, $concrete);
                }
                $containerConfigurator->singleton($abstract, $concrete);
            }
        }

        if (!empty($requiredConfig['definitions'])) {
            foreach ($requiredConfig['definitions'] as $abstract => $concrete) {
                if ($this->enableCache) {
                    $this->compiledDefinitions .= $this->genVal($abstract, $concrete);
                }
                $containerConfigurator->bind($abstract, $concrete);
            }
        }
    }

    private function loadFromCallback(callable $requiredConfig): void
    {
        $instanceResolver = new InstanceResolver($this->getContainer());

//        /** @var ArrayContainerConfigurator $containerConfigurator */
//        $containerConfigurator = $instanceResolver->create(ArrayContainerConfigurator::class);

        /** @var ContainerConfiguratorInterface $containerConfigurator */
        $containerConfigurator = $this
            ->getContainer()
            ->get(ContainerConfiguratorInterface::class);

        $methodParametersResolverArgs = [
            $containerConfigurator
        ];
        $methodParametersResolver = new MethodParametersResolver($this->getContainer());
        $params = $methodParametersResolver->resolveClosure($requiredConfig, $methodParametersResolverArgs);
//        dd($params);
        call_user_func_array($requiredConfig, $params);
    }
}
