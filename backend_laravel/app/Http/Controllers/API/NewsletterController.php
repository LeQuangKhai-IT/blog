<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsletterRequest;
use App\Models\Newsletter;
use App\Models\User;
use App\Notifications\NewsletterNotification;

class NewsletterController extends Controller
{
    /**
     * Retrieve a list of sent newsletters.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $newsletters = Newsletter::all();
        return response()->json($newsletters);
    }

    /**
     * Create and send a new newsletter.
     *
     * @param  \App\Http\Requests\NewsletterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NewsletterRequest $request)
    {

        $newsletter = Newsletter::create($request->validated());

        $recipients = User::where('newsletter', true)->get();

        return response()->json(['message' => 'Newsletter created and sent successfully.', 'newsletter' => $newsletter]);
    }

    /**
     * Delete a newsletter.
     *
     * @param  \App\Models\Newsletter  $newsletter
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return response()->json(['message' => 'Newsletter deleted successfully.']);
    }

    /**
     * Subscribe the authenticated user to the newsletter.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function subscribe()
    {
        $user = auth()->user();

        User::all()->find($user)->update(['newsletter' => true]);
        return response()->json(['message' => 'Subscribed to newsletter successfully.']);
    }

    /**
     * Unsubscribe the authenticated user from the newsletter.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unsubscribe()
    {
        $user = auth()->user();
        User::all()->find($user)->update(['newsletter' => false]);
        return response()->json(['message' => 'Unsubscribed from newsletter successfully.']);
    }
}
