<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Logic to save email or send to mailchimp
        return back()->with('success', 'Thank you for subscribing!');
    }
}
