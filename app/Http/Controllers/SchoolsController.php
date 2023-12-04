<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSchoolRequest;
use App\Models\Escuela;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SchoolsController extends Controller
{
    /**
     *  index function
     * @return \Illuminate\Contracts\View\View
     *
     */
    public function index()
    {
        $schools = Escuela::all();

        return view('schools', compact('schools'));
    }

    /**
     * create school
     * @param StoreSchoolRequest $req
     * @return \Illuminate\Http\RedirectResponse
     *
     */
    public function storeSchool(StoreSchoolRequest $req)
    {
        $validated = $req->validated();
        $school = Escuela::create($validated);

        UserController::history('creó la escuela '. $school->nombre);

        return back()->with('success', 'Escuela creada');
    }

    /**
     * List schools DataTable
     * @return mixed
     * @throws \Exception
     */
    public function listSchools()
    {
        $data = Escuela::all()->map(function($query){
            return [
                "nombre" => $query->nombre,
                "id"     => $query->id
            ];
        });

        return DataTables::of($data)->make(true);
    }


    public function showSchool (Escuela $escuela)
    {
        return view ('showSchool')->with('escuela', $escuela);
    }

    public function edit(Escuela $school, Request $request)
    {
        $beforeEditSchool = 'Versión anterior: <br>'.
                            'Nombre: '. $school->nombre;

        $school->update($request->toArray());

        UserController::history('Editó la escuela ID '. $school->id . ' <br>' . $beforeEditSchool. '<br>'.
            'Versión nueva: '. $school->nombre
        );

        return back()->with('success', 'Escuela editada');
    }

}
