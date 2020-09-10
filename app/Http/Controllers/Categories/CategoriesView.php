<?php

namespace App\Http\Controllers\Categories;

use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;

class CategoriesView
{
    public function saveCategory($request)
    {
        $category = new Category();
        $category->name = $request->input('name');
        $saveCategory = $category->save();

        if ($saveCategory) {
            return [
                "result"  => $saveCategory,
                "success" => true,
            ];
        }

        return [
            "result"  => "A apărut o eroare la adăugarea categoriei. Te rog încearcă din nou mai târziu.",
            "success" => false,
        ];
    }

    public function deleteCategory($id)
    {
        $countArticles = DB::table('articles')->where('article_category', '=', $id)->count();
        if ($countArticles) {
            return [
                "result"  => "Categoria nu a putut fi ștearsă, deoarece există articole având această categorie.",
                "success" => false,
            ];
        }

        return [
            "result"  => "Categoria poate fi ștearsă cu succes.",
            "success" => true,
        ];
    }
}
