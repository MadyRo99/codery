<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;

/**
 * Class PagesController
 * @package App\Http\Controllers
 */
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
     * @param Request $request
     * @return Factory|View
     */
    public function getHomePage(Request $request)
    {
        $search = $request->input('search');

        return view(
            'home'
        )->withHomePage(true)->withSearch($search);
    }

    /**
     * Get the About Page of the Blog.
     *
     * @return mixed
     */
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
     * Get all the articles unfiltered for the Admin Panel Page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllArticles(Request $request)
    {
        $articles = DB::table('articles')->select([
            "articles.article_category", "articles.status", "articles.created_at", "articles.description",
            "articles.main_image", "article_categories.name", "articles.slug", "articles.title"
        ])->join('article_categories', 'articles.article_category', '=', 'article_categories.id')
            ->orderBy('created_at', 'desc')->get();

        return response()->json($articles);
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
        $search = strtolower($request->input('search'));
        $searchExploaded = explode(" ", $search);

        $validator = Validator::make($request->all(), [
            'categoryId' => 'nullable|integer',
            'search' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $articles = $this->article->select([
            "articles.article_category", "articles.created_at", "articles.description",
            "articles.main_image", "article_categories.name", "articles.slug", "articles.title"
        ])->where('articles.status', '=', 1);

        if ($categoryId) {
            $articles = $articles->where('articles.article_category', '=', $request->input('categoryId'));
        }

        if ($search) {
            $articles = $articles->where(function($q) use ($search, $searchExploaded) {
                $q->where('title', 'LIKE', '%' . $search . '%')
                  ->orWhere('tags', 'LIKE', '%' . $searchExploaded[0] . '%');

                array_shift($searchExploaded);
                foreach ($searchExploaded as $word) {
                    $q->orWhere('tags', 'LIKE', '%' . $word . '%');
                }
            });
        }

        $articles = $articles->orderBy('created_at', 'desc');

        return response()->json($articles->paginate(4));
    }

    public function getAdminPanel()
    {
        return view(
            'auth.admin.adminPanel'
        )->withTitle("Panou Administrare");
    }
}
