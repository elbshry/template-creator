<?php

namespace TemplateCreator\Recipes;

abstract class PopulateStub extends AbstractRecipe
{

    protected function loadStub($stubName): bool|string
    {
        $file = sprintf('%s/src/TemplateCreator/stubs/%s', dirname(__DIR__, 3), $stubName);
        return file_get_contents($file);
    }

}