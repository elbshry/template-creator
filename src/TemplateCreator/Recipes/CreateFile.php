<?php

namespace TemplateCreator\Recipes;

use TemplateCreator\TemplateContext;

abstract class CreateFile extends AbstractRecipe
{

    protected function createFile(string $path, string $content): bool|int
    {
        $dir = dirname($path);
        if(!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return file_put_contents($path, $content);
    }

    public function prepare(TemplateContext $templateContext): RecipeInterface
    {
        if(count($templateContext->getErrors())) {
            return parent::prepare($templateContext);
        }

        $fileCreated = $this->createFile($templateContext->getFromStorage('path'), $templateContext->getFromStorage('template'));

        if($fileCreated === false) {
            $templateContext->addToErrors('file', sprintf('File %s could not be created!', $templateContext->getFromStorage('path')));
        }

        return parent::prepare($templateContext);
    }
}