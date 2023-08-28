<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Index extends Controller
{

    function __construct()
    {
    }

    function __invoke(Request $request): void
    {
        $this->controller = $request;
    }
}
