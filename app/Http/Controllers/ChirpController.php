<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('chirps.index', [
            'chirps' => Chirp::with('user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //variable en la que se van a almacenar los datos si las validaciones se cumplen, en este caso le digo que message es obligatorio, va a ser una cadena de texto y maximo de 255.
        $validate = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        //En este paso creo el registro en etodo create asociada al usuario. request->user obtiene el usuario que hace la solicitud parecido a auth::user() user->chirps asosiar la relacion de estos y crear el registro con metodo create de eloquent
        $request->user()->chirps()->create($validate);

        //finalmente, redireciona a la ruta de chirps el metodo específico de index.
        return redirect(route('chirps.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chirp $chirp): View
    {
        Gate::authorize('update', $chirp);

        return view('chirps.edit', [
            'chirp'=> $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse
    {
        //utilizo la fachada de Gate para el sist de automatizacion de roles, verifico si el usuario actual tiene permiso para modificar los datos especificados en la variable chirp, puede ser que por su rol tenga acceso a unos o otros metodos
        Gate::authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chirp $chirp): RedirectResponse
    {
        Gate::authorize('delete', $chirp);

        $chirp->delete();

        return redirect(route('chirps.index'));
    }
}
