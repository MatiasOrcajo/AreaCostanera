<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialEventRequest;
use App\Models\DescuentosCantidadegresados;
use App\Models\DinersQuantityDiscount;
use App\Models\EventPayment;
use App\Models\Menu;
use App\Models\SocialEvent;
use App\Traits\PartyTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SocialEventController extends Controller
{
    public function store(SocialEventRequest $request)
    {
        $socialEvent = new SocialEvent();
        $socialEvent->name = $request->name;
        $socialEvent->fecha = $request->date;
        $socialEvent->diners = $request->diners;
        $socialEvent->menu_id = $request->menu_id;

        $discount = $socialEvent->getDiscountByDiners();
        $totalPreDiscount = $socialEvent->diners * Menu::find($socialEvent->menu_id)->precio;
        $socialEvent->total = $totalPreDiscount - (($totalPreDiscount * $discount) / 100);

        $socialEvent->save();

        return redirect()->back();
    }

    public function show(SocialEvent $event)
    {
        $menus = Menu::getListOfSpecificMenuCategory("SOCIAL");

        return view('showSocialEvent', compact('event', 'menus'));
    }

    public function edit(Request $request, SocialEvent $event)
    {
        //Diners before edit
        $oldDinersValue = $event->diners;

        $event->name = $request->name;
        $event->fecha = $request->fecha;
        $event->diners = $request->diners;
        $event->menu_id = $request->menu_id;

        //Update total
        $event->updateTotalWhenEventIsEdited($oldDinersValue);

        return back()->with('Success', 'Evento editado');
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
     * @param EventPayment $payment
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deletePayment(EventPayment $payment)
    {
        $payment->softDelete();

        return back()->with('Success', 'Pago eliminado');
    }

    /**
     * Soft delete social event
     * @param SocialEvent $event
     * @return bool
     */
    public function softDelete(SocialEvent $event)
    {
        $event->status = 0;
        $event->save();

        return true;
    }


    /**
     * @param Request $request
     * @param SocialEvent $event
     * @return \Illuminate\Http\RedirectResponse
     */
    public function registerPayment(Request $request, SocialEvent $event)
    {

        if($request->diners_quantity > ($event->diners - $event->getCountOfPayedDishes()))
        {
            return back()->with('Errors', 'No puede registrar un pago que supere la cantidad de platos totales o por pagar');
        }

        $payment = new EventPayment();
        $payment->social_event_id = $event->id;
        $payment->payment = $event->returnMenuPriceWithDiscounts() * $request->diners_quantity;
        $payment->diners_quantity = $request->diners_quantity;
        $payment->save();

        //Update total
        $event->updateTotalForPayment($payment->diners_quantity);

        return back()->with('Success', 'Pago registrado');

    }

    /**
     * Return diners quantity discount menu
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function dashboardDinersQuantityDiscount()
    {
        $discounts = DinersQuantityDiscount::all();

        return view('socialEventDinersDiscounts', compact('discounts'));
    }

    /**
     * Create new DinersQuantityDiscount
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createDinersQuantityDiscount(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'discount' => 'required'
        ]);

        DinersQuantityDiscount::create($validated);

        return back()->with('Success', 'Descuento creado');

    }

    /**
     * Create new DinersQuantityDiscount
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editDinersQuantityDiscount(Request $request, DinersQuantityDiscount $discount)
    {
        $validated = $request->validate([
            'description' => 'required|string',
            'from' => 'required|integer',
            'to' => 'required|integer',
            'discount' => 'required'
        ]);

        $discount->update($validated);

        return back()->with('Success', 'Descuento editado');

    }


    /**
     * @return void
     *
     */
    public function listDinersQuantityDiscounts()
    {
        $data = DinersQuantityDiscount::all()
            ->map(function ($query){
                return [
                    "description" => $query->description,
                    "from" => $query->from,
                    "to" => $query->to,
                    "discount" => $query->discount."%",
                    "id" => $query->id
                ];
            });

        return DataTables::of($data)->make(true);

    }



}
