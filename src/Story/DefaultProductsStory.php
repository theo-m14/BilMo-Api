<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\ProductFactory;

final class DefaultProductsStory extends Story
{
    public function build(): void
    {
        ProductFactory::createMany(50);
    }
}
