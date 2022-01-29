<?php

namespace TemplateCreator\Recipes;

use TemplateCreator\TemplateContext;

abstract class AbstractRecipe implements RecipeInterface
{

    private RecipeInterface $nextRecipe;

    public function prepare(TemplateContext $templateContext): RecipeInterface
    {
        return $this->nextRecipe->prepare($templateContext);
    }

    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface
    {
        return $this->nextRecipe->assertIngredientsComplete($templateContext);
    }

    public function setNext(RecipeInterface $recipe): RecipeInterface
    {
        $this->nextRecipe = $recipe;
        return $recipe;
    }
}