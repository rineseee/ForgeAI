<?php

namespace App\Http\Controllers;

use App\Domain\Github\Actions\StoreGithubConnection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GithubConnectionController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['repo', 'read:user', 'user:email'])
            ->redirect();
    }

    public function callback(Request $request, StoreGithubConnection $storeGithubConnection): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $storeGithubConnection->handle($request->user(), $githubUser);

        return redirect()->route('repositories.index')->with('status', 'GitHub account connected.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->githubConnections()->delete();

        return redirect()->route('profile.edit')->with('status', 'github-disconnected');
    }
}
