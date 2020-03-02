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
        $article->slug = $request->input('slug');

        $fileName = null;
        if ($request->file('main_image')) {
            $fileExtension = $request->file('main_image')->guessExtension();
            $fileName = Helpers\generateRandomString(16) . '.' . $fileExtension;
            $request->file('main_image')->storeAs('articles/' . $article->slug . '/', $fileName, 'public');
        }

        $article->main_image = $fileName;

        $article->save();

        return $article;
    }

    /**
     * Save the images used in the article after submission.
     *
     * @param $request
     * @return bool
     */
    public function saveArticleImages($request)
    {
        $slug = $request->input('slug');
        $images = $request->file('items');

        foreach ($images as $image) {
            $imageExtension = $image->getMimeType();
            $imageName = $image->getClientOriginalName();
            $allowedExtensions = ["image/jpeg", "image/png"];

            if (!in_array($imageExtension, $allowedExtensions)) {
                return false;
            }

            $image->storeAs('articles/' . $slug . '/', $imageName, 'public');

            return true;
        }
    }
}
