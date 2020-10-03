<?php

namespace App\Http\Controllers\Newsletters;

use App\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\NewsletterExists;
use App\Rules\NewsletterStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class NewslettersController
 * @package App\Http\Controllers\Newsletters
 */
class NewslettersController extends Controller
{
    /**
     * Join Newsletter Subscription and send confirmation Token on mail.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function joinNewsletter(Request $request)
    {
        $rules = [
            'email' => ['bail', 'required', 'email', new NewsletterStatus(), new NewsletterExists()],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => $validator->getMessageBag()->toArray(),
                'success'  => false,
            ], 200);
        }

        $email = $request->input('email');

        $token = Str::random(32);

        $newsletterView = new NewslettersView();
        $createNewsletter = $newsletterView->createNewsletter($email, $token);

        if ($createNewsletter) {
            $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
            $beautymail->send('mails.joinNewsletter', [
                'confirmNewsletter' => "http://localhost:8000/confirmNewsletter/" . $token
            ], function($message) use ($email)
            {
                $message->from('coderyromania@gmail.com')
                        ->to($email)
                        ->subject('Salutare! Confirmă solicitarea ta de înscriere la Newsletter');
            });

            return response()->json([
                'response' => 'Un email pentru confirmarea abonării a fost trimis la adresa specificată.',
                'success'  => true,
            ], 200);
        }

        return response()->json([
            'response' => 'Se pare că a apărut o problemă la trimiterea confirmării pentru abonarea la Newsletter. Te rog încearcă din nou mai târziu.',
            'success'  => false,
        ], 200);
    }

    /**
     * Confirm the Newsletter Subscription based on the given Token.
     *
     * @param $token
     * @return mixed
     * @throws BindingResolutionException
     */
    public function confirmNewsletter($token)
    {
        $newsletterView = new NewslettersView();
        $confirmNewsletter = $newsletterView->confirmNewsletter($token);

        if ($confirmNewsletter['success']) {
            if (!$confirmNewsletter["subscribed"]) {
                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('mails.confirmedNewsletter', [
                    'token' => $token
                ], function($message) use ($confirmNewsletter)
                {
                    $message->from('coderyromania@gmail.com')
                        ->to($confirmNewsletter["email"])
                        ->subject('Bine ai venit! Ai confirmat cu succes abonarea ta la Newsletter');
                });
            }

            return view(
                'newsletters.infoNewsletter'
            )->withTitle("Confirmare Newsletter")->withInfo($confirmNewsletter["result"])->withToken($token)->withSubscribed($confirmNewsletter["subscribed"]);
        } else {
            abort(404);
        }
    }

    /**
     * Delete the Newsletter Subscription based on the given token.
     *
     * @param $token
     * @return mixed
     */
    public function deleteNewsletter($token)
    {
        $newsletterView = new NewslettersView();
        $deleteNewsletter = $newsletterView->deleteNewsletter($token);

        if ($deleteNewsletter['success']) {
            return view(
                'newsletters.infoNewsletter'
            )->withTitle("Dezabonare Newsletter")->withInfo($deleteNewsletter["result"])->withToken($token)->withSubscribed(false);
        } else {
            abort(404);
        }
    }

    /**
     * Get the Articles Newsletter Sending Page.
     *
     * @return mixed
     */
    public function getArticlesNewsletter()
    {
        return view(
            'newsletters.articlesNewsletter'
        )->withTitle("Trimite Newsletter");
    }

    /**
     * Get the Basic Info for the Articles.
     *
     * @return JsonResponse
     */
    public function getArticlesBasicInfo()
    {
        $date = \Carbon\Carbon::today()->subDays(31);

        $articles = Article::select(['id', 'title'])
                    ->where('created_at', '>=', $date)
                    ->where('status', '=', 1)->get();

        return response()->json([
            'response' => $articles,
            'success'  => true,
        ], 200);

    }

    /**
     * Send the Newsletter to all the active Subscribers.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws BindingResolutionException
     */
    public function sendNewsletter(Request $request)
    {
        $rules = [
            'title'   => 'required|string|min:2',
            'message' => 'required|string|min:10'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'response' => "Te rog verifică câmpurile și încearcă din nou.",
                'success'  => false,
            ], 200);
        }

        $newsletterSubscriptions = DB::table('newsletter_subscriptions')
                                    ->select(["email", "token"])
                                    ->where("active", "=", 1)
                                    ->get();

        if (count($newsletterSubscriptions)) {
            $title = $request->input("title");
            $content = $request->input("message");
            $articles = $request->input('newsletterArticles');

            $newsletterArticles = Article::select('title', 'description', 'slug', 'main_image')
                                    ->whereIn('id', $articles)->get();

            foreach ($newsletterSubscriptions as $subscription) {
                $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
                $beautymail->send('mails.articlesNewsletter', [
                    'title'       => $title,
                    'content'     => $content,
                    'articles'    => $newsletterArticles,
                    'token'       => $subscription->token,
                ], function($message) use ($subscription, $title) {
                    $message->from('coderyromania@gmail.com')
                        ->to($subscription->email)
                        ->subject($title);
                });
            }

            return response()->json([
                'response' => "Newsletter-ul a fost trimis cu succes.",
                'success'  => true,
            ], 200);
        } else {
            return response()->json([
                'response' => "Se pare că nu există abonați disponibili.",
                'success'  => false,
            ], 200);
        }
    }
}
