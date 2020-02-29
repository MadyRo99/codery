<?php

namespace App\Http\Controllers\Articles;

use App\Article;
use App\Helpers;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;

class ArticlesView
{
    /**
     * Index View.
     *
     * @return Factory|View
     */
    public function index()
    {
        return view(
            'articles.index'
        );
    }

    /**
     * Get the Create Articles View.
     *
     * @return Factory|View
     */
    public function getCreateArticlePage()
    {
        return view(
            'articles.create'
        );
    }

    /**
     * Save the article in the database.
     *
     * @param $request
     * @return Article
     */
    public function saveArticle($request)
    {
        $article = new Article();

        $article->title = $request->input('title');
        $article->author_id = Auth::user()->id;
        $article->article_category = $request->input('article_category');
        $article->content = $request->input('content');
        $article->est_time = $request->input('est_time');
        $article->slug = Helpers\generateSlug($request->input('title'), 10);

        $article->save();

        return $article;
    }
}
