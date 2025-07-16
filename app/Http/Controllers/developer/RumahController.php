<?php

namespace App\Http\Controllers\developer;

use App\Http\Controllers\Controller;
use App\Http\Requests\developer\RumahRequest;
use App\Models\Rumah;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class RumahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = new Rumah;
        $rumah = $data->rumahAll();
        return Inertia::render('developer/rumah/Index', [
            'rumah' => $rumah,
        ]);
    }

    public function create()
    {
        return Inertia::render('developer/rumah/Form');
    }


    public function store(RumahRequest $request): RedirectResponse
    {
    
        Rumah::create($request->validated());

        return redirect()->route('developer.rumah.index')->with('success', 'Data rumah berhasil ditambahkan.');
    }


    public function editInit(Request $request): RedirectResponse
    {
        // dd($request->all());
        $request->session()->put('edit_rumah_id', $request->id);
        return redirect()->route('developer.rumah.edit');
    }

    public function edit(Request $request)
    {
        $id = $request->session()->get('edit_rumah_id');

        $data = Rumah::find($id);

        if (!$data) {
            return redirect()->route('developer.rumah.index')
                ->with('error', 'Data rumah tidak ditemukan.');
        }

        return Inertia::render('developer/rumah/Form', [
            'data' => $data,
        ]);
    }

    public function update(RumahRequest $request, Rumah $rumah): RedirectResponse
    {
        try {
            $rumah->update($request->validated());

            return redirect()->route('developer.rumah.index')
                ->with('success', 'Data rumah berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data.');
        }
    }


    public function destroy($id): RedirectResponse
    {
        try {
            $rumah = Rumah::findOrFail($id);
            $rumah->delete();

            return redirect()->route('developer.rumah.index')
                ->with('success', 'Data rumah berhasil dihapus.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('developer.rumah.index')
                ->with('error', 'Data rumah tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->route('developer.rumah.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}
