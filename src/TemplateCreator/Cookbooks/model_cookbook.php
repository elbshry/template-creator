<?php

return [
    \TemplateCreator\Recipes\Model\PopulateModelStub::class,
    \TemplateCreator\Recipes\Model\CreateModelFile::class,

    // I would like to use a Null object to avoid null checks
    \TemplateCreator\Recipes\NullRecipe::class,
];