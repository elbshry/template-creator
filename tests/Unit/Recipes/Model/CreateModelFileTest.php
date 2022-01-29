<?php

namespace TemplateCreator\Tests\Unit\Recipes\Model;

use Faker\Factory;
use TemplateCreator\Recipes\Model\CreateModelFile;
use PHPUnit\Framework\TestCase;
use TemplateCreator\Recipes\NullRecipe;
use TemplateCreator\TemplateContext;
use TemplateCreator\Tests\Unit\PHPUnitUtil;

class CreateModelFileTest extends TestCase
{

    public function testAssertIngredientsCompleteDoesNotAddPathWhenErrorsFound()
    {
        $templateContextStub = $this->createMock(TemplateContext::class);
        $templateContextStub->method('getErrors')
            ->willReturn(['error1' => 'error description 1']);
        $templateContextStub->expects($this->never())
            ->method('addToStorage');
        $templateContextStub->expects($this->never())
            ->method('addToErrors');

        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $nullRecipeMock->method('assertIngredientsComplete')
            ->willReturnSelf();

        $createModelRecipe = new CreateModelFile();
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->assertIngredientsComplete($templateContextStub));

    }

    public function testAssertIngredientsCompleteAddToStorage()
    {
        $templateContextStub = $this->createMock(TemplateContext::class);
        $templateContextStub->method('getErrors')
            ->willReturn([]);
        $templateContextStub->expects($this->once())
            ->method('addToStorage');
        $templateContextStub->expects($this->never())
            ->method('addToErrors');

        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $nullRecipeMock->method('assertIngredientsComplete')
            ->willReturnSelf();

        $createModelRecipe = new CreateModelFile();
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->assertIngredientsComplete($templateContextStub));

    }

    /**
     * @dataProvider nameAndScopeProvider
     */
    public function testAssertIngredientsCompleteAddToErrors($class, $scope)
    {
        $templateContextStub = $this->createMock(TemplateContext::class);
        $templateContextStub->method('getErrors')
            ->willReturn([]);
        $templateContextStub->method('getScope')
            ->willReturn($scope);
        $templateContextStub->method('getFromStorage')
            ->willReturn($class);
        $templateContextStub->expects($this->once())
            ->method('addToStorage');
        $templateContextStub->expects($this->once())
            ->method('addToErrors');

        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $nullRecipeMock->method('assertIngredientsComplete')
            ->willReturnSelf();

        // create the file to allow the class adding an error
        $path = sprintf('%s%s%s/%s.php', dirname(__DIR__, 4), '/Models/', implode('/', $scope), $class);
        mkdir(dirname($path), 0777, true);
        file_put_contents($path, 1);
        $this->assertFileExists($path);
        $createModelRecipe = new CreateModelFile();
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->assertIngredientsComplete($templateContextStub));
        PHPUnitUtil::deleteDirectory(dirname(__DIR__, 4) . '/Models/');

        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 4) . '/Models/');
    }

    public function testPrepareDoesNotAddToContextWhenErrorFound()
    {
        $templateContextStub = $this->createMock(TemplateContext::class);
        $templateContextStub->method('getErrors')
            ->willReturn(['error1' => 'error description 1']);
        $templateContextStub->expects($this->never())
            ->method('getFromStorage');
        $templateContextStub->expects($this->never())
            ->method('addToErrors');

        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $nullRecipeMock->method('prepare')
            ->willReturnSelf();

        $createModelRecipe = new CreateModelFile();
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->prepare($templateContextStub));
    }

    public function testPrepareGetFromStorageWhenErrorNotFound()
    {
        $templateContextStub = $this->createMock(TemplateContext::class);
        $templateContextStub->method('getErrors')
            ->willReturn([]);
        $templateContextStub->expects($this->exactly(2))
            ->method('getFromStorage')
            ->withConsecutive(['path'], ['template'])
            ->willReturnOnConsecutiveCalls(dirname(__DIR__, 4) . '/Models/DummyModel.php', '<?php');

        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $nullRecipeMock->method('prepare')
            ->willReturnSelf();

        $createModelRecipe = new CreateModelFile();
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->prepare($templateContextStub));

        PHPUnitUtil::deleteDirectory(dirname(__DIR__, 4) . '/Models');
        $this->assertDirectoryDoesNotExist(dirname(__DIR__, 4) . '/Models');
    }

    public function testSetNext()
    {
        $createModelRecipe = new CreateModelFile();
        $nullRecipeMock = $this->createMock(NullRecipe::class);
        $createModelRecipe->setNext($nullRecipeMock);
        $this->assertSame($nullRecipeMock, $createModelRecipe->setNext($nullRecipeMock));

    }


    public function nameAndScopeProvider(): array
    {
        $faker = Factory::create();
        $arr = function($faker) {
            $ret = [];
            for($i=0; $i<3; $i++) {
                $ret[] = str_replace(' ', '', ucwords($faker->words(3, true)));

            }
            return $ret;
        };

        $ret = [];
        for($i=0; $i<3; $i++) {
            $ret[] = [ucfirst($faker->word()), $arr($faker)];
        }
        return $ret;
    }
}
