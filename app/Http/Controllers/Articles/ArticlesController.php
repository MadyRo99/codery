<?php

namespace App\Http\Controllers\Articles;

use App\Article;
use App\Helpers;
use Carbon\Carbon;
use Illuminate\View\View;
use App\Rules\ArticleStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Storage;
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
        $article = Article::where('slug', '=', $slug)->first();

        if ($article && $article->status) {
            return view(
                'articles.index'
            )->withSlug($slug)->withTitle($article->title);
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
                'articles.title', 'articles.content', 'articles.est_time', 'articles.created_at', 'articles.main_image',
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
     * Edit an article.
     *
     * @param $slug
     * @return mixed
     */
    public function getUpdateArticlePage($slug)
    {
        $article = Article::where('slug', '=', $slug)->first();

        if ($article) {
            if ($article->author->id == Auth::user()->id || Auth::user()->role == 2) {
                return view(
                    'articles.edit'
                )->withSlug($slug)->withTitle($article->title);
            }

            abort(401);
        }

        abort(404);
    }

    /**
     * Fetch the Article Data from DB for editing based on the given Slug.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchUpdateArticleData(Request $request)
    {
        $slug = $request->input('slug');

        $articleData = Article::where('articles.slug', '=', $slug)->first();

        if ($articleData) {
            if ($articleData->author->id == Auth::user()->id || Auth::user()->role == 2) {
                $articlePath = "articles/" . $slug . "/";
                $filesInFolder = Storage::allFiles($articlePath);
                $imagesNames = [];

                foreach($filesInFolder as $path) {
                    $file = pathinfo($path);;
                    array_push($imagesNames, $file['basename']);
                }

                $articleData->images = $imagesNames;

                $categories = DB::table('article_categories')->get();

                return response()->json([
                    'response' => [
                        "article"    => $articleData,
                        "categories" => $categories->pluck('name', 'id')
                    ],
                    'success'  => true,
                ], 200);
            }

            return response()->json([
                'response' => ["slug" => "Se pare ca nu ai acces pentru a accesa aceasta resursa."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => $articleData,
            'success'  => false,
        ], 200);
    }

    /**
     * Update the edited article.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateArticle(Request $request)
    {
        $rules = [
            'title'             => 'required|string|max:75|min:5',
            'article_category'  => 'required|integer|exists:article_categories,id',
            'content'           => 'required|min:10',
            'est_time'          => 'required|integer',
            'slug'              => 'required|string|min:18',
            'status'            => ['required', new ArticleStatus],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $article = Article::where('slug', '=', $request->input('slug'))->first();

        if ($article) {
            if ($article->author->id == Auth::user()->id || Auth::user()->role == 2) {
                $articleView = new ArticlesView();
                $updatedArticle = $articleView->updateArticle($request);

                if ($updatedArticle["success"]) {
                    return response()->json([
                        'response' => $updatedArticle["result"],
                        'success'  => true,
                    ], 200);
                }

                return response()->json([
                    'response' => ["main_image" => $updatedArticle["result"]],
                    'success'  => false,
                ], 200);
            }

            return response()->json([
                'response' => ["slug" => "Se pare ca nu ai acces pentru a accesa aceasta resursa."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => ["slug" => "Articolul nu a putut fi gasit. Te rog incearca din nou mai tarziu."],
            'success'  => false,
        ], 200);
    }

    /**
     * Delete an image from an article.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Request $request)
    {
        $rules = [
            'slug'      => 'required|string|min:18',
            'imageName' => 'required|string'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $slug = $request->input('slug');
        $imageName = $request->input('imageName');

        $article = Article::where('slug', '=', $slug)->first();

        if ($article) {
            if ($article->author->id == Auth::user()->id || Auth::user()->role == 2) {
                $mainImage = false;
                if ($article->main_image == $imageName) {
                    $mainImage = true;
                    $article->main_image = null;
                    $article->save();
                }

                $articleView = new ArticlesView();
                $deleteImage = $articleView->deleteImage($slug, $imageName);

                return response()->json([
                    'mainImage' => $mainImage,
                    'success'   => $deleteImage,
                ], 200);
            }

            return response()->json([
                'response' => ["slug" => "Se pare ca nu ai acces pentru a accesa aceasta resursa."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => ["slug" => "Articolul nu a putut fi gasit. Te rog incearca din nou mai tarziu."],
            'success'  => false,
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
            'slug'              => 'required|string|min:18',
            'main_image'        => 'nullable|mimes:jpeg,png',
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

        if ($savedArticle["success"]) {
            return response()->json([
                'response' => $savedArticle["result"],
                'success'  => true,
            ], 200);
        }

        return response()->json([
            'response' => ["main_image" => $savedArticle["result"]],
            'success'  => false,
        ], 200);
    }

    /**
     * Save the images used in the article after submission.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function saveArticleImages(Request $request)
    {
        $rules = [
            'slug'  => 'required|string|exists:articles,slug',
            'items' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'info' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $article = Article::where('slug', '=', $request->input('slug'))->first();

        if ($article) {
            if ($article->author->id == Auth::user()->id || Auth::user()->role == 2) {
                $articleView = new ArticlesView();
                $savedArticle = $articleView->saveArticleImages($request);

                if ($savedArticle["success"]) {
                    return response()->json([
                        'response' => $savedArticle["result"],
                        'info'     => "Imaginile articolului au fost incarcate cu succes.",
                        'success'  => true,
                    ], 200);
                } else {
                    return response()->json([
                        'info'     => ["images" => $savedArticle["result"]],
                        'success'  => false,
                    ], 200);
                }
            }

            return response()->json([
                'response' => ["slug" => "Se pare ca nu ai acces pentru a accesa aceasta resursa."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => ["slug" => "Articolul nu a putut fi gasit. Te rog incearca din nou mai tarziu."],
            'success'  => false,
        ], 200);
    }

    /**
     * Delete an article.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteArticle(Request $request)
    {
        $rules = [
            'slug'  => 'required|string|exists:articles,slug',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $article = Article::where('slug', '=', $request->input('slug'))->first();

        if ($article) {
            if ($article->author->id == Auth::user()->id || Auth::user()->role == 2) {
                $articleView = new ArticlesView();
                $deleteArticle = $articleView->deleteArticle($article->slug);

                if ($deleteArticle) {
                    if ($article->delete()) {
                        return response()->json([
                            'response' => true,
                            'success'  => true,
                        ], 200);
                    }
                }

                return response()->json([
                    'response' => ["A aparut o eroare la stergerea articolului. Te rog incearca din nou mai tarziu."],
                    'success'  => false,
                ], 200);
            }

            return response()->json([
                'response' => ["slug" => "Se pare ca nu ai acces pentru a accesa aceasta resursa."],
                'success'  => false,
            ], 200);
        }

        return response()->json([
            'response' => ["slug" => "Articolul nu a putut fi gasit. Te rog incearca din nou mai tarziu."],
            'success'  => false,
        ], 200);
    }
}