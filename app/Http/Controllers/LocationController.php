<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Http\Requests\UpdateLocationRequest;
use App\Repositories\LocationRepository;
use App\Models\Location;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LocationController extends Controller
{
    public function __construct(
        protected LocationRepository $locationRepo
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $locations = $this->locationRepo->paginateWithFilters($filters);

        return view('locations.index', compact('locations', 'filters'));
    }

    public function create(): View
    {
        return view('locations.create');
    }

    public function store(StoreLocationRequest $request): RedirectResponse
    {
        $this->locationRepo->create($request->validated());

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $location = Location::findOrFail($id);
        return view('locations.edit', compact('location'));
    }

    public function update(UpdateLocationRequest $request, int $id): RedirectResponse
    {
        $this->locationRepo->update($id, $request->validated());

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $location = Location::findOrFail($id);

        // Enforce Restrict Delete: Check if referenced by facilities
        if ($location->facilities()->count() > 0) {
            return redirect()->back()->with('error', 'Lokasi ini tidak dapat dihapus karena masih digunakan oleh beberapa fasilitas pelabuhan (Constraint Rule).');
        }

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus.');
    }
}
