<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InvitationController extends Controller
{
    /**
     * Generate a secure single-use invitation token for an instructor.
     */
    public function sendInvite(Request $request)
    {
        // 1. Validate that the admin provided a valid, unique email address
        $request->validate([
            'email' => 'required|email|unique:users,email|unique:teacher_invitations,email'
        ]);

        // 2. Generate a secure, completely unpredictable cryptographic token
        $token = Str::random(40);

        // 3. Save the token into our database table, expiring in 48 hours
        DB::table('teacher_invitations')->insert([
            'email' => $request->email,
            'token' => $token,
            'expires_at' => Carbon::now()->addDays(2),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        // 4. Build the unique registration link that the teacher will click
        $inviteLink = url('/register?token=' . $token);

        // Return back to the dashboard with a success message and the link
        return back()->with([
            'success' => 'Teacher invitation generated successfully!',
            'invite_link' => $inviteLink
        ]);
    }
}