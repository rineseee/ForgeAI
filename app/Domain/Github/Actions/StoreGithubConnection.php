<?php

namespace App\Domain\Github\Actions;

use App\Models\GithubConnection;
use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class StoreGithubConnection
{
    /**
     * Links the authenticated user to their GitHub identity and persists
     * the OAuth token for later repository imports/syncs.
     */
    public function handle(User $user, SocialiteUser $githubUser): GithubConnection
    {
        $connection = GithubConnection::updateOrCreate(
            ['github_id' => (string) $githubUser->getId()],
            [
                'user_id' => $user->id,
                'nickname' => $githubUser->getNickname(),
                'access_token' => $githubUser->token,
                'refresh_token' => $githubUser->refreshToken ?? null,
                'scopes' => $githubUser->approvedScopes ?? [],
                'connected_at' => now(),
            ]
        );

        $user->forceFill([
            'github_id' => $githubUser->getId(),
            'avatar_url' => $githubUser->getAvatar(),
            'email_verified_at' => $user->email_verified_at ?? now(),
        ])->save();

        return $connection;
    }
}
