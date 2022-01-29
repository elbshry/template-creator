<?php

namespace TemplateCreator\Recipes\Migration;

use TemplateCreator\Recipes\PopulateStub;
use TemplateCreator\Recipes\RecipeInterface;
use TemplateCreator\TemplateContext;
use function Symfony\Component\String\s;

class PopulateMigrationStub extends PopulateStub
{

    private const VARIABLE_LIST = ['{class_name}'];
    private const STUB_NAME = 'migration.stub';

    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface
    {
        $string = s($templateContext->getName())->camel()->title();
        // camel should remove all non letters and uc words except the first letter
        // title should set the first letter to upper case
        // isEmpty will check if the string is empty after removing all non-letter chars
        if($string->isEmpty()) {
            $templateContext->addToErrors('name', sprintf('Value %s is empty or invalid', $templateContext->getName()));
        }

        // add the class name to the context
        $templateContext->addToStorage('class', $string->toString());

        return parent::assertIngredientsComplete($templateContext);
    }


    public function prepare(TemplateContext $templateContext): RecipeInterface
    {
        $stub = $this->loadStub(self::STUB_NAME);

        if($stub === false) {
            $templateContext->addToErrors('stub', sprintf('Unable to get the contents of the stub %s', self::STUB_NAME));
            return parent::prepare($templateContext);
        }

        $className = $templateContext->getFromStorage('class');
        // replace all the variables with values
        $template = str_replace(self::VARIABLE_LIST, compact( 'className'), $stub);
        // save the template content to the context
        $templateContext->addToStorage('template', $template);

        return parent::prepare($templateContext);
    }
}