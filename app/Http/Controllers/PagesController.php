<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;

class PagesController extends Controller
{
    /**
     * @var Article
     */
    private $article;

    /**
     * PagesController constructor.
     *
     * @param Request $request
     * @param Article $article
     */
    public function __construct(Article $article, Request $request)
    {
        $this->article = $article->join('article_categories', 'articles.article_category', 'article_categories.id');
    }

    /**
     * Get the Home Page of the Blog.
     *
     * @return Factory|View
     */
    public function getHomePage()
    {
        return view(
            'acasa'
        )->withHomePage(true);
    }

    public function getAboutPage()
    {
        return view(
            'about'
        )->withTitle("Despre");
    }

    /**
     * Get the Categories available for the articles.
     *
     * @return JsonResponse
     */
    public function getCategories()
    {
        $categories = DB::table('article_categories')->get();

        return response()->json([
            'response' => $categories,
            'success'  => true,
        ], 200);
    }

    /**
     * Get the articles for the required page and paginate them.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getArticles(Request $request)
    {
        $categoryId = $request->input('categoryId');

        $validator = Validator::make($request->all(), [
            'categoryId' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $articles = $this->article;
        if ($categoryId) {
            $articles = $articles->where('articles.article_category', '=', $request->input('categoryId'));
        }

        return response()->json($articles->paginate(2));
    }
}
