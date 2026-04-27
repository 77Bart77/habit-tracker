<?php

namespace App\Http\Controllers;

use App\Services\GoalCategoryService;
use Illuminate\Http\Request;

class GoalCategoryController extends Controller
{
    public function __construct(private GoalCategoryService $service)
    {
        
    }

    public function index()
    {
        $categories = $this->service->getAll();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->service->create($request->all());
        return redirect()->route('categories.index')->with('success', 'Kategoria utworzona!');
    }

    public function edit($id)
    {
        $category = $this->service->getById($id);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $this->service->update($id, $request->all());
        return redirect()->route('categories.index')->with('success', 'Zaktualizowano kategorię!');
    }

    public function delete($id)
    {
        $this->service->delete($id);
        return redirect()->route('categories.index')->with('success', 'Usunięto kategorię.');
    }
}
