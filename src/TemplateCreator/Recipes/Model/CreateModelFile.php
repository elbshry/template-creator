<?php

namespace TemplateCreator\Recipes\Model;

use TemplateCreator\Recipes\CreateFile;
use TemplateCreator\Recipes\RecipeInterface;
use TemplateCreator\TemplateContext;

class CreateModelFile extends CreateFile
{

    private const ROOT_DIR = '/Models/';

    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface
    {
        if(count($templateContext->getErrors())) {
            return parent::assertIngredientsComplete($templateContext);
        }

        // assert model does not exist
        $path = sprintf('%s%s%s/%s.php', dirname(__DIR__, 4), self::ROOT_DIR, implode('/', $templateContext->getScope()), $templateContext->getFromStorage('class'));
        if(file_exists($path)) {
            $templateContext->addToErrors('file', sprintf('File %s already exists!', $path));
        }

        $templateContext->addToStorage('path', $path);

        return parent::assertIngredientsComplete($templateContext);
    }
}