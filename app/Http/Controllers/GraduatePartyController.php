<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGraduatePartyRequest;
use App\Models\Constants;
use App\Models\Dia;
use App\Models\Egresados;
use App\Models\Escuela;
use App\Models\FormasPago;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GraduatePartyController extends Controller
{

    public function index()
    {
        $graduateParties = Egresados::all()->map(function($query) {
            return $query->where(Carbon::parse($query->fecha), '<=', Carbon::now());
        });
        $escuelas = Escuela::all();
        $dias = Dia::all();
        $menus = Menu::all();
        $formasPago = FormasPago::all();

        return view('dashboard', compact('graduateParties', 'escuelas', 'dias', 'menus', 'formasPago'));
    }


    /**
     * Create new graduate party
     * @param StoreGraduatePartyRequest $request
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function createGraduateParty(StoreGraduatePartyRequest $request)
    {
        $validated = $request->validated();
        $graduateDate = Carbon::createFromFormat('Y-m-d', $validated['fecha'])->format('d-m-Y');
//        $paymentDate = Carbon::createFromFormat('Y-m-d', $validated['fecha_pago'])->format('d-m-Y');

        Egresados::create([
            'escuela_id' => $validated['escuela_id'],
            'curso' => $validated['curso'],
            'fecha' => $graduateDate,
//            'fecha_pago' => $paymentDate,
            'dia_id' => $validated['dia_id'],
//            'menu_id' => $validated['menu_id'],
            'slug' => Str::slug($validated['escuela_id'] . '-' . $validated['curso'] . '-' . $graduateDate),
//            'forma_pago_id' => $validated['forma_pago_id']
        ]);

        return back()->with('success', 'Fiesta añadida');
    }


    public function showGraduateParty($slug)
    {
        $event = Egresados::where('slug', $slug)->first();

        return view ('showEvent', compact('event'));
    }
}
