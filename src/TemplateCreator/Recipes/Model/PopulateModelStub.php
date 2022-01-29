<?php

namespace TemplateCreator\Recipes\Model;

use TemplateCreator\Recipes\PopulateStub;
use TemplateCreator\Recipes\RecipeInterface;
use TemplateCreator\TemplateContext;
use function Symfony\Component\String\s;

class PopulateModelStub extends PopulateStub
{

    private const NAMESPACE_PREFIX = 'App\Models';
    private const VARIABLE_LIST = ['{namespace}', '{class_name}', '{table_name}'];
    private const STUB_NAME = 'model.stub';

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

        // same validation for all scope values
        $newScope = [];
        foreach ($templateContext->getScope() as $key => $value) {
            $string = s($value)->camel()->title();
            if($string->isEmpty()) {
                $templateContext->addToErrors(printf('scope %d', $key), sprintf('Value %s is empty or invalid', $value));
            }
            $newScope[] = $string->toString();
        }

        // update the scope in the context
        $templateContext->setScope($newScope);

        return parent::assertIngredientsComplete($templateContext);
    }


    public function prepare(TemplateContext $templateContext): RecipeInterface
    {
        $stub = $this->loadStub(self::STUB_NAME);

        if($stub === false) {
            $templateContext->addToErrors('stub', sprintf('Unable to get the contents of the stub %s', self::STUB_NAME));
            return parent::prepare($templateContext);
        }

        $namespace = self::NAMESPACE_PREFIX;

        if(count($templateContext->getScope())) {
            $namespace .= sprintf('\\%s', implode('\\', $templateContext->getScope()));
        }
        $className = $templateContext->getFromStorage('class');
        // I prefer to use snake naming convention for table names
        // no problem to use slug if you are already using it, here is an example
        // $slugger = new \Symfony\Component\String\Slugger\AsciiSlugger();
        // $tableName = $slugger->slug($templateContext->getName());
        $tableName = s($templateContext->getName())->snake()->toString();
        // replace all the variables with values
        $template = str_replace(self::VARIABLE_LIST, compact('namespace', 'className', 'tableName'), $stub);
        // save the template content to the context
        $templateContext->addToStorage('template', $template);

        return parent::prepare($templateContext);
    }
}