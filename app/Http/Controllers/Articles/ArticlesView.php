<?php

namespace App\Http\Controllers\Articles;

use App\Article;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * Class ArticlesView
 * @package App\Http\Controllers\Articles
 */
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
     * Upload the main image of the article.
     *
     * @param $image
     * @param $slug
     * @return array|string
     */
    private function uploadMainImage($image, $slug)
    {
        if ($image->getSize() && $image->getSize() >= 1048576) {
            return [
                "result"  => "Se pare ca a aparut o problema la incarcarea imaginii. Te rog verifica ca imaginea principala sa nu depaseasca 1MB.",
                "success" => false,
            ];
        }

        try {
            $imageExtension = $image->getMimeType();
            $imageName = $image->getClientOriginalName();
            $allowedExtensions = ["image/jpeg", "image/png", "image/svg+xml"];

            if (!in_array($imageExtension, $allowedExtensions)) {
                return [
                    "result"  => "Se pare ca a aparut o problema la incarcarea imaginii. Te rog verifica ca imaginea principala sa aiba una dintre extensiile: 'png', 'jpg', 'jpeg', 'svg'.",
                    "success" => false,
                ];
            }

            if (Storage::exists("articles/" . $slug . "/" . $imageName)) {
                return [
                    "result"  => "Se pare ca a aparut o problema la incarcarea imaginii. Exista deja o imagine cu acest nume.",
                    "success" => false,
                ];
            }

            $image->storeAs("articles/" . $slug . "/", $imageName, 'public');

            return [
                "result"  => $imageName,
                "success" => true,
            ];
        } catch (\Exception $e) {
            return [
                "result"  => "Se pare ca a aparut o problema la incarcarea imaginii principale. Te rog incearca din nou mai tarziu.",
                "success" => false,
            ];
        }
    }

    /**
     * Save the article in the database.
     *
     * @param $request
     * @return array
     */
    public function saveArticle($request)
    {
        $slug = $request->input('slug');
        $article = new Article();

        $article->title = $request->input('title');
        $article->author_id = Auth::user()->id;
        $article->article_category = $request->input('article_category');
        $article->content = $request->input('content');
        $article->description = $request->input('description');
        $article->est_time = $request->input('est_time');
        $article->slug = $slug;

        $tags = explode(",", $request->input('tags'));
        $tags = array_map(function ($tag) {
            return trim($tag);
        }, $tags);

        $article->tags = strtolower(json_encode($tags));

        $image = $request->file('main_image');

        if ($image) {
            $imageName = $this->uploadMainImage($image, $slug);
            if ($imageName["success"]) {
                $article->main_image = $imageName["result"];
                $article->save();

                return [
                    "result"  => $article,
                    "success" => true,
                ];
            }

            return [
                "result"  => $imageName["result"],
                "success" => false,
            ];
        }

        $article->save();

        return [
            "result"  => $article,
            "success" => true,
        ];
    }

    /**
     * Update the article in the database.
     *
     * @param $request
     * @return array|mixed
     */
    public function updateArticle($request)
    {
        $slug = $request->input('slug');
        $article = Article::where('slug', '=', $slug)->first();

        $article->title = $request->input('title');
        $article->article_category = $request->input('article_category');
        $article->content = $request->input('content');
        $article->description = $request->input('description');
        $article->est_time = $request->input('est_time');
        $article->status = $request->input('status');

        $tags = explode(",", $request->input('tags'));
        $tags = array_map(function ($tag) {
            return trim($tag);
        }, $tags);

        $article->tags = strtolower(json_encode($tags));

        $image = $request->file('main_image');

        if ($image) {
            $imageName = $this->uploadMainImage($image, $slug, $article->main_image);
            if ($imageName["success"]) {
                $article->main_image = $imageName["result"];
                $article->save();

                return [
                    "result"  => $article,
                    "success" => true,
                ];
            }

            return [
                "result"  => $imageName["result"],
                "success" => false,
            ];
        }

        $article->save();

        return [
            "result"  => $article,
            "success" => true,
        ];
    }

    /**
     * Save the images used in the article after submission.
     *
     * @param $request
     * @return array
     */
    public function saveArticleImages($request)
    {
        $slug = $request->input('slug');
        $images = $request->file('items');

        $savedImages = [];
        foreach ($images as $image) {
            if ($image->getSize() && $image->getSize() >= 1048576) {
                return [
                    "result"  => "Se pare ca a aparut o problema la incarcarea imaginilor. Te rog verifica ca imaginile sa nu depaseasca 1MB fiecare.",
                    "success" => false,
                ];
            }

            try {
                $imageExtension = $image->getMimeType();
                $imageName = $image->getClientOriginalName();
                $allowedExtensions = ["image/jpeg", "image/png", "image/svg+xml"];

                if (!in_array($imageExtension, $allowedExtensions)) {
                    return [
                        "result"  => "Se pare ca a aparut o problema la incarcarea imaginilor. Te rog verifica ca toate imaginile sa aiba una dintre extensiile: 'png', 'jpg', 'jpeg', 'svg'.",
                        "success" => false,
                    ];
                }

                if (Storage::exists("articles/" . $slug . "/" . $imageName)) {
                    return [
                        "result"  => "Se pare ca a aparut o problema la incarcarea imaginilor. Te rog verifica daca exista deja o imagine cu unul dintre numele folosite.",
                        "success" => false,
                    ];
                }

                $image->storeAs('articles/' . $slug . '/', $imageName, 'public');
                array_push($savedImages, $imageName);
            } catch (\Exception $e) {
                return [
                    "result"  => "Se pare ca a aparut o problema la incarcarea imaginilor. Te rog incearca din nou mai tarziu.",
                    "success" => false,
                ];
            }
        }

        return [
            "result"  => $savedImages,
            "success" => true,
        ];
    }

    /**
     * Delete an image from an article.
     *
     * @param $slug
     * @param $imageName
     * @return bool
     */
    public function deleteImage($slug, $imageName)
    {
        $imagePath = "articles/" . $slug . "/" . $imageName;

        if (Storage::exists($imagePath)) {
            return Storage::delete($imagePath);
        }

        return null;
    }

    /**
     * Delete the article's folder.
     *
     * @param $slug
     * @return bool
     */
    public function deleteArticle($slug)
    {
        $folderPath = "articles/" . $slug;
        $articleFolder = Storage::exists($folderPath);

        if ($articleFolder) {
            if (Storage::deleteDirectory($folderPath)) {
                return true;
            }

            return false;
        }

        return true;
    }
}
