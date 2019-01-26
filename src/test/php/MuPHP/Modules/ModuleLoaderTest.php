<?php
/**
 * Created by PhpStorm.
 * User: andrew
 * Date: 26/01/2019
 * Time: 19:33
 */

namespace MuPHP\Modules;

use PHPUnit\Framework\TestCase;

class ModuleLoaderTest extends TestCase
{
    private function getModuleLoader(string $fixture)
    {
        return ModuleLoader::dynamicallyLoadModules(getcwd() . '/src/test/fixtures/' . $fixture);
    }

    public function testSimpleModuleFetch()
    {
        $moduleLoader = $this->getModuleLoader('testSimpleModule');
        $result = $moduleLoader->getModuleForCategory('SimpleModule');

        $this->assertInstanceOf('MuPHP\\Modules\\ModuleDefinition', $result);
    }

    /**
     * @expectedException MuPHP\Modules\ModuleException
     */
    public function testSimpleModuleFetchFailure()
    {
        $moduleLoader = $this->getModuleLoader('testSimpleModule');
        $result = $moduleLoader->getModuleForCategory('ComplexModule');

        $this->assertInstanceOf('MuPHP\\Modules\\ModuleDefinition', $result);
    }

    public function testMultiModuleFetch()
    {
        $moduleLoader = $this->getModuleLoader('testVariabledModule');
        $results = $moduleLoader->getModulesForCategory('VariableModule');

        $this->assertCount(3, $results);

        foreach ($results as $result) {
            $this->assertInstanceOf('MuPHP\\Modules\\ModuleDefinition', $result);
        }
    }

    public function testVariableFilteredFetch()
    {
        $moduleLoader = $this->getModuleLoader('testVariabledModule');
        $results = $moduleLoader->getModulesForCategory('VariableModule', ['Test2' => 'True']);

        $this->assertCount(1, $results);

        $result = array_pop($results);
        $this->assertInstanceOf('MuPHP\\Modules\\ModuleDefinition', $result);
    }

    public function testVariableFilteredFetchFailure()
    {
        $moduleLoader = $this->getModuleLoader('testVariabledModule');
        $results = $moduleLoader->getModulesForCategory('VariableModule', ['Test2' => 'False']);

        $this->assertCount(0, $results);
    }
}
