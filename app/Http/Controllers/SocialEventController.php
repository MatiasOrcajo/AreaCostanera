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
        $event->updateTotalForDiscount();


        return back()->with('Success', 'Descuento agregado');
    }

    public function editDiscount(Request $request, SocialEvent $event)
    {
        $event->discount = $request->discount;
        $event->save();
        $event->updateTotalForDiscount();

        return back()->with('Success', 'Descuento editado');

    }


    /**
     * @param Request $request
     * @param SocialEvent $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerPayment(Request $request, SocialEvent $event)
    {
        $payment = new EventPayment();
        $payment->social_event_id = $event->id;
        $payment->payment = $event->menu->precio * $request->diners_quantity;
        $payment->diners_quantity = $request->diners_quantity;
        $payment->save();

        //Update total
        $event->updateTotalForPayment($payment->diners_quantity);

        return back()->with('Success', 'Pago registrado');

    }



}
