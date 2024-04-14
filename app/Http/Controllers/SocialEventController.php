<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialEventRequest;
use App\Models\SocialEvent;
use App\Traits\PartyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SocialEventController extends Controller
{
    public function store(SocialEventRequest $request)
    {
        $socialEvent = new SocialEvent();
        $socialEvent->name = $request->name;
        $socialEvent->fecha = $request->date;
        $socialEvent->diners = $request->diners;
        $socialEvent->menu_id = $request->menu_id;
        $socialEvent->slug = Str::slug($request->name.'-'.$request->date);

        $socialEvent->save();

        return redirect()->back();
    }

    public function show($slug)
    {
        $socialEvent = SocialEvent::where('slug', $slug)->first();
        dd($socialEvent);
    }

}
