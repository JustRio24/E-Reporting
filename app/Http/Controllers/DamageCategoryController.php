<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageCategoryRequest;
use App\Http\Requests\UpdateDamageCategoryRequest;
use App\Repositories\DamageCategoryRepository;
use App\Models\DamageCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DamageCategoryController extends Controller
{
    public function __construct(
        protected DamageCategoryRepository $categoryRepo
    ) {}

    public function index(Request $request): View
    {
        $filters = $request->only(['search']);
        $categories = $this->categoryRepo->paginateWithFilters($filters);

        return view('damage-categories.index', compact('categories', 'filters'));
    }

    public function create(): View
    {
        return view('damage-categories.create');
    }

    public function store(StoreDamageCategoryRequest $request): RedirectResponse
    {
        $this->categoryRepo->create($request->validated());

        return redirect()->route('damage-categories.index')->with('success', 'Kategori kerusakan berhasil ditambahkan.');
    }

    public function edit(int $id): View
    {
        $category = DamageCategory::findOrFail($id);
        return view('damage-categories.edit', compact('category'));
    }

    public function update(UpdateDamageCategoryRequest $request, int $id): RedirectResponse
    {
        $this->categoryRepo->update($id, $request->validated());

        return redirect()->route('damage-categories.index')->with('success', 'Kategori kerusakan berhasil diperbarui.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $category = DamageCategory::findOrFail($id);

        // Check constraint: check if category is used in reports
        if ($category->damageReports()->count() > 0) {
            return redirect()->back()->with('error', 'Kategori kerusakan ini tidak dapat dihapus karena masih digunakan oleh beberapa laporan kerusakan (Constraint Rule).');
        }

        $category->delete();

        return redirect()->route('damage-categories.index')->with('success', 'Kategori kerusakan berhasil dihapus.');
    }
}
