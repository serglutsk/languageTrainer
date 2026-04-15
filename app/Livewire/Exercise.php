<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Dictionary;
use App\Models\DictionaryTrainingHistory;
use Livewire\Component;

class Exercise extends Component
{
    public string $value = '';

    public bool $use_all = false;

    public bool $with_example = false;

    public ?Dictionary $word = null;

    public DictionaryTrainingHistory $history;

    public function mount(): void
    {
        $this->history = app(DictionaryTrainingHistory::class);
        $this->pickNextWord();
    }

    public function render()
    {
        return view('livewire.exercise', [
            'word' => $this->word,
            'history' => $this->history->today(),
        ]);
    }

    public function nextWord(): void
    {
        $this->word->update([
            'amount_right' => $this->word->amount_right++,
            'status' => $this->word->amount_right > 10 ? true : false,
        ]);
        $this->saveUserHistory();
        $this->pickNextWord();
        $this->value = '';
    }

    public function speak(int $wordId): void
    {
        $word = Dictionary::query()
            ->whereKey($wordId)
            ->where('user_id', auth()->id())
            ->where('language_id', auth()->user()?->language_id)
            ->firstOrFail();

        $this->dispatch('speak', text: $word->lang_text, lang: 'en-US');
    }

    public function speakExample(int $wordId): void
    {
        $word = Dictionary::query()
            ->whereKey($wordId)
            ->where('user_id', auth()->id())
            ->where('language_id', auth()->user()?->language_id)
            ->firstOrFail();

        if (! filled($word->example)) {
            return;
        }

        $this->dispatch('speak', text: $word->example, lang: 'en-US');
    }

    private function saveUserHistory(): void
    {
        if ($this->history->exists()) {
            $this->history->update([
                'amount_right' => $this->history->amount_right++,
            ]);
        } else {
            $this->history->create([
                'user_id' => auth()->id(),
                'amount_right' => 1,
            ]);
        }
    }

    private function pickNextWord(): void
    {
        $query = Dictionary::query()
            ->where('user_id', auth()->id())
            ->where('language_id', auth()->user()?->language_id);

        // TODO: if $use_all is false, we can filter by status/new cards.
        $this->word = $query->inRandomOrder()
            ->first();
    }
}
