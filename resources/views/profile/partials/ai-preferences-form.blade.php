<section>
    <header>
        <h2 class="text-lg font-semibold text-slate-900 dark:text-white">
            {{ __('AI Preferences') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
            {{ __('Control how Forge AI analyzes your repositories.') }}
        </p>
    </header>

    <form method="post" action="{{ route('preferences.ai.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="preferred_ai_model" value="Preferred AI model" />
            <select
                id="preferred_ai_model" name="preferred_ai_model"
                class="mt-1 block w-full rounded-lg border-slate-300 bg-white text-sm shadow-sm focus:border-slate-500 focus:ring-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100"
            >
                <option value="">{{ __('Platform default (:model)', ['model' => config('services.openai.model')]) }}</option>
                @foreach (\App\Models\User::AI_MODELS as $model)
                    <option value="{{ $model }}" @selected($user->preferred_ai_model === $model)>{{ $model }}</option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ __('gpt-4o-mini is faster and cheaper. gpt-4o is slower but more thorough.') }}</p>
        </div>

        <x-forms.toggle
            name="include_source_in_analysis"
            :checked="$user->include_source_in_analysis"
            label="Include source code in analysis"
            description="When enabled, real file contents from your connected repositories are sent to the AI for deeper, more accurate analysis. Disable to analyze using only metadata and commit history."
        />

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'ai-preferences-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-500 dark:text-slate-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
