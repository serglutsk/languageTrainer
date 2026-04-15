<div>
    @if (! $word)
        <flux:callout variant="warning" heading="{{ __('No words found') }}">
            {{ __('Add words to your dictionary first.') }}
        </flux:callout>
    @else
    <form
        class="space-y-6"
        x-data="{ checked: false, showExample: false, showExpected: false }"
        x-on:reset.prevent="
                $wire.value = '';
                checked = false;
                showExample = false;
                showExpected = false;
                "
        x-on:submit.prevent="
            const expected = ($refs.expected?.textContent || '').trim().toLowerCase();
            const answer = ($wire.value || '').trim().toLowerCase();

            if (!checked) {
                if (answer !== '' && answer === expected) {
                    checked = true;
                    showExample = !!$wire.with_example;
                    showExpected = false;
                } else {
                    checked = false;
                    showExample = false;
                    showExpected = true;
                }
                return;
            }

            checked = false;
            showExample = false;
            showExpected = false;
            $wire.nextWord();
        "
    >
        <div>
            <flux:heading size="lg">{{ __('Do Exercise') }}</flux:heading>
            <flux:subheading size="lg">{{ $word->translation}}</flux:subheading>
        </div>

        <flux:input
            label="{{ __('Answer') }}"
            wire:model="value"
            required
            x-on:input="showExpected = false; checked = false; showExample = false"
        />

        <span class="hidden" x-ref="expected">{{ $word->lang_text }}</span>

        <div x-show="showExpected" x-cloak>
            <flux:callout variant="warning" heading="{{ __('Correct answer') }}">
                {{ $word->lang_text }}
            </flux:callout>
        </div>

        <div x-show="showExample" x-cloak>
            <flux:callout variant="info" heading="{{ __('Example') }}">
                {{ $word->example }}
            </flux:callout>
        </div>

        <flux:field variant="inline">
            <flux:checkbox wire:model="use_all" />

            <flux:label>{{ __('Use All')}}</flux:label>

            <flux:error name="terms" />
        </flux:field>
        <flux:field variant="inline">
            <flux:checkbox wire:model="with_example" />

            <flux:label>{{ __('With example')}}</flux:label>

            <flux:error name="terms" />
        </flux:field>
        <div class="flex">
            <flux:spacer />
            <flux:button.group>
            <flux:button type="submit" variant="primary">{{ __('Send') }}</flux:button>

            <flux:tooltip :content="__('Pronounce')" position="bottom">
                <flux:button
                    variant="ghost"
                    icon="play-circle"
                    size="sm"
                    wire:click="speak({{ $word->id }})"
                    aria-label="{{ __('Pronounce') }}"
                />
            </flux:tooltip>
            @if($word->example)
                <flux:tooltip :content="__('Pronounce example')" position="bottom">
                    <flux:button
                        variant="ghost"
                        icon="play"
                        size="sm"
                        wire:click="speakExample({{ $word->id }})"
                        aria-label="{{ __('Pronounce example') }}"
                    />
                </flux:tooltip>

            @endif
            <flux:button type="reset" variant="primary" color="indigo">{{ __('Reset') }}</flux:button>
            </flux:button.group>
        </div>
    </form>

    <script>
        function keepOnlyEnglishAndPunctuation(text) {
            // This regex removes characters that belong to Cyrillic or other non-Latin scripts
            // while trying to preserve the flow of the English sentence.
            return text
                .replace(/[^\p{Script=Latin}\p{Punctuation}\p{Separator}\p{Number}]/gu, '')
                .replace(/\s+/g, ' ')
                .trim();
        }
       
        
            Livewire.on('speak', ({ text, lang }) => {
                if (!('speechSynthesis' in window)) {
                    return;
                }

                const synth = window.speechSynthesis;

                if (synth.speaking) {
                    synth.cancel();
                }
                text = keepOnlyEnglishAndPunctuation(text);
                if (!text) {
                    return;
                }

                const utter = new SpeechSynthesisUtterance(text);
                utter.lang = lang || 'en-US';

                const voices = synth.getVoices?.() ?? [];
                const preferred = voices.find(v => v.lang === utter.lang) ?? voices.find(v => v.lang?.startsWith('en'));

                if (preferred) {
                    utter.voice = preferred;
                }

                synth.speak(utter);
            });

    </script>
    @endif
    <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
        <flux:spacer />
        <flux:progress value="{{!$history->amount_right?0:$history->amount_right}}" max="100" />
    </div>
</div>
