<?php

namespace App\Services;

use App\Models\GoalCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GoalCategoryService
{
    // Pobierz listę kategorii (globalne + użytkownika)
    public function getAll()
    {
        return GoalCategory::query()
            ->whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->orderByRaw("user_id IS NOT NULL") // najpierw globalne
            ->orderBy('name')
            ->get();
    }

    // Tworzenie kategorii
    public function create(array $data)
    {
        $this->validate($data);

        return GoalCategory::create([
            'name'    => $data['name'],
            'color'   => $data['color'],
            'user_id' => Auth::id(), // każdy user tworzy swoje
        ]);
    }

    // Pobranie kategorii użytkownika
    public function getById($id)
    {
        return GoalCategory::query()
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    // Aktualizacja kategorii
    public function update($id, array $data)
    {
        $category = $this->getById($id);
        $this->validate($data, $id);

        $category->update($data);

        return $category;
    }

    // Usunięcie kategorii
    public function delete($id)
    {
        $category = $this->getById($id);
        $category->delete();
    }

    // Walidacja
    protected function validate(array $data, $id = null)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:30',
                // unikalność per użytkownik
                function ($attr, $value, $fail) use ($id) {
                    $exists = GoalCategory::query()
                        ->where('name', $value)
                        ->where('user_id', Auth::id())
                        ->when($id, fn($q) => $q->where('id', '!=', $id))
                        ->exists();

                    if ($exists) {
                        $fail('Masz już kategorię o takiej nazwie.');
                    }
                }
            ],
            'color' => 'required|string|max:20',
        ];

        Validator::make($data, $rules)->validate();
    }
}
