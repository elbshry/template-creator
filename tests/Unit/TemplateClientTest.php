<?php
namespace TemplateCreator\Tests\Unit;

use TemplateCreator\Exceptions\CookbookNotFoundException;
use TemplateCreator\TemplateClient;
use PHPUnit\Framework\TestCase;

class TemplateClientTest extends TestCase
{

    public function testAssertCookbookExists()
    {
        $templateClient = new TemplateClient('model');
        $result = PHPUnitUtil::callMethod($templateClient, 'assertCookbookExists', ['model']);
        $this->assertNotNull($result);

        $result = PHPUnitUtil::callMethod($templateClient, 'assertCookbookExists', ['migration']);
        $this->assertNotNull($result);

        $this->expectException(CookbookNotFoundException::class);
        PHPUnitUtil::callMethod($templateClient, 'assertCookbookExists', ['notfoundcookbook']);
    }

}
