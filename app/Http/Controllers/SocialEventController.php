<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialEventRequest;
use App\Models\EventPayment;
use App\Models\Menu;
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
        $socialEvent->total = $socialEvent->diners * Menu::find($socialEvent->menu_id)->precio;

        $socialEvent->save();

        return redirect()->back();
    }

    public function show(SocialEvent $event)
    {
        return view('showSocialEvent', compact('event'));
    }

    public function registerDiscount(Request $request, SocialEvent $event)
    {

        $event->discount += $request->discount;
        $event->save();

        return back()->with('Success', 'Descuento agregado');
    }

    public function editDiscount(Request $request, SocialEvent $event)
    {
        $event->discount = $request->discount;
        $event->save();

        return back()->with('Success', 'Descuento editado');

    }

    public function registerPayment(Request $request, SocialEvent $event)
    {
        $payment = new EventPayment();
        $payment->social_event_id = $event->id;
        $payment->payment = $request->payment;

        return back()->with('Success', 'Pago registrado');

    }



}
