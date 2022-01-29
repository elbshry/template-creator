<?php

return [
    \TemplateCreator\Recipes\Migration\PopulateMigrationStub::class,
    \TemplateCreator\Recipes\Migration\CreateMigrationFile::class,

    // I would like to use a Null object to avoid null checks
    \TemplateCreator\Recipes\NullRecipe::class,
];