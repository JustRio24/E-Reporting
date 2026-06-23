<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFacilityCategoryRequest;
use App\Http\Requests\UpdateFacilityCategoryRequest;
use App\Repositories\FacilityCategoryRepository;
use App\Models\FacilityCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FacilityCategoryController extends Controller
{
    public function __construct(
        protected FacilityCategoryRepository $categoryRepo
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $categories = $this->categoryRepo->paginateWithFilters($filters);
        
        return view('facility-categories.index', compact('categories', 'filters'));
    }

    public function create(): View
    {
        return view('facility-categories.create');
    }

    public function store(StoreFacilityCategoryRequest $request): RedirectResponse
    {
        $this->categoryRepo->create($request->validated());

        return redirect()->route('facility-categories.index')->with('success', 'Kategori fasilitas berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $category = FacilityCategory::findOrFail($id);
        return view('facility-categories.edit', compact('category'));
    }

    public function update(UpdateFacilityCategoryRequest $request, int $id): RedirectResponse
    {
        $this->categoryRepo->update($id, $request->validated());

        return redirect()->route('facility-categories.index')->with('success', 'Kategori fasilitas berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = FacilityCategory::findOrFail($id);
        
        // Enforce Restrict Delete: Check if referenced by facilities
        if ($category->facilities()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori ini tidak dapat dihapus karena masih digunakan oleh beberapa fasilitas pelabuhan (Constraint Rule).');
        }

        $category->delete();

        return redirect()->route('facility-categories.index')->with('success', 'Kategori fasilitas berhasil dihapus.');
    }
}
