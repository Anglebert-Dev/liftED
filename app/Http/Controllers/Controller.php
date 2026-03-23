<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successRedirect(string $route, string $message): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route($route)->with('success', $message);
    }

    protected function errorRedirect(string $message): \Illuminate\Http\RedirectResponse
    {
        return redirect()->back()->withErrors(['error' => $message]);
    }
}
