<?php

namespace GeekBrains\LevelTwo\HTTP\Actions;

use GeekBrains\LevelTwo\http\Request;
use GeekBrains\LevelTwo\http\Response;

interface ActionInterface
{
    public function handle(Request $request): Response;
}
