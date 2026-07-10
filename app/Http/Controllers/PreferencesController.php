<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PreferencesController extends Controller
{
    public function updateAi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'preferred_ai_model' => ['nullable', Rule::in(User::AI_MODELS)],
            'include_source_in_analysis' => ['sometimes', 'boolean'],
        ]);

        $request->user()->update([
            'preferred_ai_model' => $validated['preferred_ai_model'] ?? null,
            'include_source_in_analysis' => $request->boolean('include_source_in_analysis'),
        ]);

        return redirect()->route('profile.edit')->with('status', 'ai-preferences-updated');
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme_preference' => ['required', Rule::in(User::THEMES)],
        ]);

        $request->user()->update($validated);

        return redirect()->route('profile.edit')->with('status', 'theme-preferences-updated');
    }

    public function updateNotifications(Request $request): RedirectResponse
    {
        $request->user()->update([
            'notify_analysis_complete' => $request->boolean('notify_analysis_complete'),
            'notify_sync_complete' => $request->boolean('notify_sync_complete'),
            'notify_github_connected' => $request->boolean('notify_github_connected'),
            'notify_on_errors' => $request->boolean('notify_on_errors'),
        ]);

        return redirect()->route('profile.edit')->with('status', 'notification-preferences-updated');
    }
}
