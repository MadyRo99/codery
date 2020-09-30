<?php

namespace App\Http\Controllers\Newsletters;

use App\Newsletter;

/**
 * Class NewslettersView
 * @package App\Http\Controllers\Newsletters
 */
class NewslettersView
{
    /**
     * Create Newsletter Subscription.
     *
     * @param $email
     * @param $token
     * @return bool
     */
    public function createNewsletter($email, $token)
    {
        $newsletter = new Newsletter();

        $newsletter->email = $email;
        $newsletter->token = $token;

        if ($newsletter->save()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Confirm the Newsletter Subscription based on the given Token.
     *
     * @param $token
     * @return array
     */
    public function confirmNewsletter($token)
    {
        $newsletter = Newsletter::where('token', '=', $token)->first();

        if ($newsletter) {
            if (!$newsletter->active) {
                $newsletter->active = true;
                $newsletter->save();

                return [
                    "result"     => "Felicitari! Abonamentul la Newsletter este de acum activ.",
                    "subscribed" => false,
                    "email"      => $newsletter->email,
                    "success"    => true,
                ];
            }

            return [
                "result"     => "Abonamentul la Newsletter este deja confirmat pentru această adresă de email.",
                "subscribed" => true,
                "success"    => true,
            ];
        }

        return [
            "result"  => "Token inexistent.",
            "success" => false,
        ];
    }

    /**
     * Delete the Newsletter Subscription based on the given token.
     *
     * @param $token
     * @return array
     */
    public function deleteNewsletter($token)
    {
        $newsletter = Newsletter::where('token', '=', $token)->first();

        if ($newsletter) {
            $newsletter->delete();

            return [
                "result"  => "Te-ai dezabonat cu succes de la Newsletter.",
                "success" => true,
            ];
        }

        return [
            "result"  => "Token inexistent.",
            "success" => false,
        ];
    }
}
