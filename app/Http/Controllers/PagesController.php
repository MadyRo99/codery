<?php

namespace App\Http\Controllers;

use App\Article;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    /**
     * @var Article
     */
    private $article;

    /**
     * PagesController constructor.
     * @param Article $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * Get the Home Page of the Blog.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getHomePage()
    {
        return view(
            'acasa'
        );
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticles()
    {
        $articles = $this->article->paginate(5);

        return response()->json($articles);
    }
}
