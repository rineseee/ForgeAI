<?php

namespace App\Http\Controllers;

use App\Domain\Github\Actions\StoreGithubConnection;
use App\Support\Toast;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GithubConnectionController extends Controller
{
    public function redirect(Request $request): RedirectResponse
    {
        if (blank(config('services.github.client_id')) || blank(config('services.github.client_secret'))) {
            Toast::error($request->user(), 'GitHub integration is not configured yet. Please contact support.');

            return redirect()->route('profile.edit')->with('status', 'GitHub connection is not configured.');
        }

        return Socialite::driver('github')
            ->scopes(['repo', 'read:user', 'user:email'])
            ->redirect();
    }

    public function callback(Request $request, StoreGithubConnection $storeGithubConnection): RedirectResponse
    {
        try {
            $githubUser = Socialite::driver('github')->user();
        } catch (Throwable $e) {
            Toast::error($request->user(), 'Could not connect your GitHub account. Please try again.');

            return redirect()->route('profile.edit')->with('status', 'GitHub connection failed.');
        }

        $storeGithubConnection->handle($request->user(), $githubUser);

        Toast::success($request->user(), 'notify_github_connected', 'GitHub account connected successfully.');

        return redirect()->route('repositories.index')->with('status', 'GitHub account connected.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->githubConnections()->delete();

        return redirect()->route('profile.edit')->with('status', 'github-disconnected');
    }
}
