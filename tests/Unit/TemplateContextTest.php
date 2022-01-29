<?php
namespace TemplateCreator\Tests\Unit;

use Faker\Factory;
use TemplateCreator\TemplateContext;
use PHPUnit\Framework\TestCase;

class TemplateContextTest extends TestCase
{

    /**
     * @dataProvider nameAndScopeProvider
     */
    public function test__construct($name, $scope)
    {
        $templateContext = new TemplateContext($name, $scope);
        $this->assertEquals($name, $templateContext->getName());
        $this->assertEquals($scope, $templateContext->getScope());
    }

    /**
     * @dataProvider keyValueProvider
     */
    public function testGetFromStorage($key, $value)
    {
        $templateContext = new TemplateContext('fake-name', []);
        $templateContext->addToStorage($key, $value);

        $this->assertEquals($value, $templateContext->getFromStorage($key));
    }

    /**
     * @dataProvider keyValueProvider
     */
    public function testGetErrors($key, $value)
    {
        $templateContext = new TemplateContext('fake-name', []);
        $templateContext->addToErrors($key, $value);

        $errors = $templateContext->getErrors();
        $this->assertCount(1, $errors);
        $this->assertNotNull($errors[$key]);
        $this->assertEquals($value, $errors[$key]);
    }

    public function nameAndScopeProvider(): array
    {
        $faker = Factory::create();
        $ret = [];
        for($i=0; $i<3; $i++) {
            $ret[] = [$faker->word(), $faker->words()];
        }
        return $ret;
    }

    public function keyValueProvider(): array
    {
        $faker = Factory::create();
        $ret = [];
        for($i=0; $i<3; $i++) {
            $ret[] = [$faker->unique()->word(), $faker->word()];
        }
        return $ret;
    }
}
