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
        Escuela::create($validated);

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
        $school->update($request->toArray());

        return back()->with('success', 'Escuela editada');
    }

}
