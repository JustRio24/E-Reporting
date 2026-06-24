<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacilityRequest;
use App\Http\Requests\UpdateFacilityRequest;
use App\Repositories\FacilityRepository;
use App\Repositories\FacilityCategoryRepository;
use App\Repositories\LocationRepository;
use App\Services\ImageService;
use App\Models\Facility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FacilityController extends Controller
{
    public function __construct(
        protected FacilityRepository $facilityRepo,
        protected FacilityCategoryRepository $categoryRepo,
        protected LocationRepository $locationRepo,
        protected ImageService $imageService,
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['category_id', 'location_id', 'search']);
        $facilities = $this->facilityRepo->paginateWithRelations($filters);
        
        $categories = $this->categoryRepo->all();
        $locations = $this->locationRepo->all();

        return view('facilities.index', compact('facilities', 'categories', 'locations', 'filters'));
    }

    public function create(): View
    {
        $categories = $this->categoryRepo->all();
        $locations = $this->locationRepo->all();
        
        return view('facilities.create', compact('categories', 'locations'));
    }

    public function store(StoreFacilityRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle photo upload with compression
        if ($request->hasFile('photo')) {
            $data['photo_path'] = $this->imageService->compressAndStore(
                $request->file('photo'),
                'facilities',
                2048 // 2MB max
            );
        }

        $this->facilityRepo->create($data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $facility = Facility::findOrFail($id);
        $categories = $this->categoryRepo->all();
        $locations = $this->locationRepo->all();

        return view('facilities.edit', compact('facility', 'categories', 'locations'));
    }

    public function update(UpdateFacilityRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        $facility = Facility::findOrFail($id);

        // Handle photo upload with compression
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($facility->photo_path) {
                Storage::disk('public')->delete($facility->photo_path);
            }
            $data['photo_path'] = $this->imageService->compressAndStore(
                $request->file('photo'),
                'facilities',
                2048 // 2MB max
            );
        }

        $this->facilityRepo->update($id, $data);

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $facility = Facility::findOrFail($id);

        // Check if there are damage reports on this facility
        if ($facility->damageReports()->count() > 0) {
            return redirect()->back()->with('error', 'Fasilitas ini tidak dapat dihapus karena terdapat laporan kerusakan yang terikat (Constraint Rule).');
        }

        // Delete photo if exists
        if ($facility->photo_path) {
            Storage::disk('public')->delete($facility->photo_path);
        }

        $facility->delete();

        return redirect()->route('facilities.index')->with('success', 'Fasilitas berhasil dihapus.');
    }
}
