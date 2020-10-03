<?php

namespace App\Http\Controllers\Categories;

use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;

/**
 * Class CategoriesController
 * @package App\Http\Controllers\Categories
 */
class CategoriesController extends Controller
{
    /**
     * Get the Categories available for the articles.
     *
     * @return JsonResponse
     */
    public function getCategories()
    {
        $categories = DB::select("
            SELECT article_categories.id AS id, article_categories.name AS name, COUNT(articles.id) AS posts
            FROM article_categories
            LEFT JOIN articles ON article_categories.id = articles.article_category
            GROUP BY article_categories.id, article_categories.name
        ");

        return response()->json([
            'response' => $categories,
            'success'  => true,
        ], 200);
    }

    /**
     * Get the Categories Page.
     *
     * @return mixed
     */
    public function getCategoriesList()
    {
        return view(
            'auth.admin.categories'
        )->withTitle("Categorii");
    }

    /**
     * Create a new category.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function createCategory(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:15|min:2',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $categoriesView = new CategoriesView();
        $savedCategory = $categoriesView->saveCategory($request);

        if ($savedCategory["success"]) {
            return response()->json([
                'response' => $savedCategory["result"],
                'success'  => true,
            ], 200);
        }

        return response()->json([
            'response' => ["A apărut o eroare la adăugarea categoriei. Te rog încearcă din nou mai târziu."],
            'success'  => false,
        ], 200);
    }

    /**
     * Delete a category by the given ID.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteCategory(Request $request)
    {
        $rules = [
            'id' => 'required|integer|exists:article_categories,id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $category = Category::where('id', '=', $request->input('id'))->first();

        if ($category) {
            $categoriesView = new CategoriesView();
            $countArticles = $categoriesView->countArticles($category->id);

            if ($countArticles["success"]) {
                if ($category->delete()) {
                    return response()->json([
                        'response' => true,
                        'success'  => true,
                    ], 200);
                }
            } else {
                return response()->json([
                    'response' => ["Categoria nu a putut fi ștearsă, deoarece există articole având această categorie."],
                    'success'  => false,
                ], 200);
            }

            return response()->json([
                'response' => ["A apărut o eroare la ștergerea categoriei. Te rog încearcă din nou mai târziu."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => ["id" => "Categoria nu a putut fi găsită. Te rog încearcă din nou mai târziu."],
            'success'  => false,
        ], 200);
    }

    /**
     * Edit a category by the given ID and new Name.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function editCategory(Request $request)
    {
        $rules = [
            'id'   => 'required|integer|exists:article_categories,id',
            'name' => 'required|string|max:15|min:2',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $id   = $request->input('id');
        $name = $request->input('name');

        $category = Category::where('id', '=', $id)->first();

        if ($category) {
            $categoriesView = new CategoriesView();
            $editCategory = $categoriesView->editCategory($id, $name);

            if ($editCategory["success"]) {
                return response()->json([
                    'response' => "Categoria a fost editată cu succes.",
                    'success'  => true,
                ], 200);
            } else {
                return response()->json([
                    'response' => "Categoria nu a putut fi editată. Te rog încearcă din nou mai târziu.",
                    'success'  => false,
                ], 200);
            }
        }

        return response()->json([
            'response' => ["id" => "Categoria nu a putut fi găsită. Te rog încearcă din nou mai târziu."],
            'success'  => false,
        ], 200);
    }
}
