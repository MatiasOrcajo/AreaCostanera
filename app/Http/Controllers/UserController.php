<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
/*
 * Crear registro en el historial
 */
    public static function history($message): void
    {
        $history            = new History();
        $history->user      = Auth::user()->name;
        $history->message   = $message;

        $history->save();
    }
}
