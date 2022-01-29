<?php

namespace TemplateCreator\Tests\Functional;

use Faker\Factory;
use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\TemplateClient;
use PHPUnit\Framework\TestCase;
use TemplateCreator\TemplateContext;
use TemplateCreator\Tests\Unit\PHPUnitUtil;
use function Symfony\Component\String\s;

class TemplateClientTest extends TestCase
{

    /**
     * @dataProvider successModelDataProvider
     */
    public function testCreateModelSuccessfully($name, $scope)
    {
        $templateClient = new TemplateClient('model');
        $templateContext = new TemplateContext($name, $scope);

        $templateClient->create($templateContext);
        $path = [];
        foreach ($scope as $item) {
            $path[] = s($item)->camel()->title()->toString();
        }
        $name = ucfirst($name);
        if(count($path)) {
            $path = sprintf('%s/Models/%s/%s.php', dirname(__DIR__, 2   ), implode('/', $path), $name);
        } else {
            $path = sprintf('%s/Models/%s.php', dirname(__DIR__, 2   ), $name);
        }
        $this->assertFileExists($path);

        PHPUnitUtil::deleteDirectory(dirname(__DIR__, 2) . '/Models');
        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 2) . '/Models');
    }

    /**
     * @dataProvider successMigrationDataProvider
     */
    public function testCreateMigrationSuccessfully($name)
    {
        $templateClient = new TemplateClient('migration');
        $templateContext = new TemplateContext($name);

        $templateClient->create($templateContext);
        $path = sprintf('%s/migrations', dirname(__DIR__, 2   ));
        $files = glob(sprintf("%s/*%s_%s*", $path, $name, date('Y_m_d_hi')));
        $this->assertCount(1, $files);

        PHPUnitUtil::deleteDirectory(dirname(__DIR__, 2) . '/migrations');
        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 2) . '/migrations');
    }

    /**
     * @dataProvider failDataProvider
     */
    public function testCreateFail($cookbook, $name, $scope)
    {
        $templateClient = new TemplateClient($cookbook);
        $templateContext = new TemplateContext($name, $scope);
        $this->expectException(CookbookNotFoundException::class);
        $templateClient->create($templateContext);
    }

    public function successModelDataProvider(): array
    {
        $faker = Factory::create();
        $ret = [];
        for($i=0; $i<5; $i++) {
            $ret[] = [$faker->unique()->word(), $faker->words($faker->randomElement([3, 2, 5, 1, 0]))];
        }
        return $ret;
    }

    public function successMigrationDataProvider(): array
    {
        $faker = Factory::create();
        $ret = [];
        for($i=0; $i<5; $i++) {
            $ret[] = [$faker->unique()->word()];
        }
        return $ret;
    }

    public function failDataProvider(): array
    {
        $faker = Factory::create();
        $ret = [];
        for($i=0; $i<3; $i++) {
            $ret[] = [$faker->word(), $faker->word(), $faker->words()];
        }
        return $ret;
    }


}
