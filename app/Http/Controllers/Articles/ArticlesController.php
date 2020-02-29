<?php

namespace App\Http\Controllers\Articles;

use App\Helpers;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;

/**
 * Class ArticlesController
 * @package App\Http\Controllers\Articles
 */
class ArticlesController extends Controller
{
    /**
     * Index View.
     *
     * @param $slug
     * @return Factory|View
     */
    public function index($slug)
    {
        $article = DB::table('articles')->where('slug', '=', $slug)->first();

        if ($article) {
            return view(
                'articles.index'
            )->withSlug($slug);
        }

        abort(404);
    }

    /**
     * Fetch the Article Data from DB based on the given Slug.
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchArticleData(Request $request)
    {
        $slug = $request->input('slug');

        $articleData = DB::table('articles')
            ->select([
                'articles.title', 'articles.content', 'articles.est_time', 'articles.created_at',
                'article_categories.id AS category_id', 'article_categories.name AS category_name',
                'users.id AS author_id', 'users.username AS author_username', 'users.avatar AS author_avatar',
            ])
            ->join('article_categories', 'articles.article_category', 'article_categories.id')
            ->join('users', 'articles.author_id', 'users.id')
            ->where('articles.slug', '=', $slug)->first();

        if (!$articleData) {
            return response()->json([
                'response' => $articleData,
                'success'  => false,
            ], 200);
        }

        $createdAt = Carbon::create($articleData->created_at);
        $articleData->created_at = Helpers\monthAbbreviation($createdAt) . " " . $createdAt->format('d') . ", " . $createdAt->format('Y');

        return response()->json([
            'response' => $articleData,
            'success'  => true,
        ], 200);
    }

    /**
     * Get the Create Articles View.
     *
     * @return Factory|View
     */
    public function getCreateArticlePage()
    {
        $articlesView = new ArticlesView();

        return $articlesView->getCreateArticlePage();
    }

    /**
     * Get the categories available for the articles.
     *
     * @return JsonResponse
     */
    public function getCategories()
    {
        $categories = DB::table('article_categories')->select(['id', 'name'])->get();

        return response()->json([
            'response' => $categories->pluck('name', 'id'),
            'success'  => true,
        ], 200);
    }

    /**
     * Save the article in the database.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveArticle(Request $request)
    {
        $rules = [
            'title'             => 'required|string|max:75|min:5',
            'article_category'  => 'required|integer|exists:article_categories,id',
            'content'           => 'required|min:10',
            'est_time'          => 'required|integer',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $articleView = new ArticlesView();
        $savedArticle = $articleView->saveArticle($request);

        return response()->json([
            'response' => $savedArticle,
            'success'  => true,
        ], 200);
    }
}
