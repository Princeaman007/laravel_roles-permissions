<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function showForm()
    {
        // Bloquer l'accès aux admins et super-admins
        if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
            abort(403, 'Accès interdit');
        }

        return view('contact.form');
    }

    public function sendMail(Request $request)
{
    if (Auth::check() && Auth::user()->hasAnyRole(['admin', 'super-admin'])) {
        abort(403, 'Accès interdit');
    }

    // Validation du formulaire
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'message' => 'required|string'
    ]);

    // Préparation des données
    $details = [
        'name' => $request->name,
        'email' => $request->email,
        'message' => $request->message
    ];

    

    // Envoi de l'e-mail
    Mail::to('tfeetude@gmail.com')->send(new ContactMail($details));

    return back()->with('message', 'Votre message a bien été envoyé !');
}

}
