<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialEventRequest;
use App\Models\SocialEvent;
use Illuminate\Http\Request;

class SocialEventController extends Controller
{

    public function store(SocialEventRequest $request)
    {
        $socialEvent = new SocialEvent();
        $socialEvent->name = $request->name;
        $socialEvent->date = $request->date;
        $socialEvent->diners = $request->diners;
        $socialEvent->menu_id = $request->menu_id;

        $socialEvent->save();

        return redirect()->back();
    }

}
