<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Recipe::where('status', 'rejected')->get();

        return view('profile.notifications', compact('notifications'));
    }
}