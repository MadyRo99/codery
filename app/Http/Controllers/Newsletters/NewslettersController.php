<?php

namespace App\Http\Controllers\Newsletters;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\NewsletterExists;
use App\Rules\NewsletterStatus;
use Illuminate\Http\JsonResponse;
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
            $beautymail->send('mails.joinNewsletter', ['confirmNewsletter' => "http://localhost:8000/confirmNewsletter/" . $token], function($message) use ($email)
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
     */
    public function confirmNewsletter($token)
    {
        $newsletterView = new NewslettersView();
        $confirmNewsletter = $newsletterView->confirmNewsletter($token);

        if ($confirmNewsletter['success']) {
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
}
