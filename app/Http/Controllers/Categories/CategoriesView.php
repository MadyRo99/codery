<?php

namespace App\Http\Controllers\Categories;

use App\Category;
use Illuminate\Support\Facades\DB;

class CategoriesView
{
    /**
     * Create a new category.
     *
     * @param $request
     * @return array
     */
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

    /**
     * Count the articles that are associated with the given category.
     * Returns "true" if there are no articles associated with the category and "false" otherwise.
     *
     * @param $id
     * @return array
     */
    public function countArticles($id)
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

    /**
     * Update a category by the given ID and new Name.
     *
     * @param $id
     * @param $name
     * @return array
     */
    public function editCategory($id, $name)
    {
        $category = Category::find($id);
        $category->name = $name;
        $category->save();

        return [
            "result"  => "Categoria a fost editată cu succes.",
            "success" => true,
        ];
    }
}
