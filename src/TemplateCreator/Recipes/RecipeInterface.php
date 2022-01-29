<?php

namespace TemplateCreator\Recipes;

use TemplateCreator\TemplateContext;

interface RecipeInterface
{

    public function prepare(TemplateContext $templateContext): RecipeInterface;

    public function assertIngredientsComplete(TemplateContext $templateContext): RecipeInterface;

    public function setNext(RecipeInterface $recipe): RecipeInterface;
}