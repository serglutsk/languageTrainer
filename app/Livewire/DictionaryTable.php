<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Forms\DictionaryForm;
use App\Models\Dictionary;
use Livewire\Component;
use Livewire\WithPagination;

class DictionaryTable extends Component
{
    use WithPagination;

    public DictionaryForm $form;

    protected $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public $search = '';

    public function render()
    {
        return view('livewire.dictionary-table', [
            'words' => Dictionary::query()
                ->where('user_id', auth()->id())
                ->where('language_id', auth()->user()?->language_id)
                ->when($this->search, fn ($query) => $query->where('lang_text', 'like', "%{$this->search}%"))
                ->orderByDesc('id')
                ->paginate(10),
        ]);
    }

    public function create(): void
    {
        $this->form->reset();
        $this->dispatch('modal-show', name: 'word-modal');
    }

    public function edit(Dictionary $word): void
    {
        abort_unless($word->user_id === auth()->id(), 403);
        abort_unless($word->language_id === auth()->user()?->language_id, 403);

        $this->form->setDictionary($word);
        $this->dispatch('modal-show', name: 'word-modal');
    }

    public function remove(Dictionary $word): void
    {
        abort_unless($word->user_id === auth()->id(), 403);
        abort_unless($word->language_id === auth()->user()?->language_id, 403);

        $word->delete();
    }

    public function save(): void
    {
        $this->form->dictionary ? $this->form->update() : $this->form->store();

        $this->dispatch('modal-close', name: 'word-modal');
    }

    public function speak(Dictionary $word): void
    {
        abort_unless($word->user_id === auth()->id(), 403);
        abort_unless($word->language_id === auth()->user()?->language_id, 403);

        $this->dispatch('speak', text: $word->lang_text, lang: 'en-US');
    }

    public function speakExample(Dictionary $word): void
    {
        abort_unless($word->user_id === auth()->id(), 403);
        abort_unless($word->language_id === auth()->user()?->language_id, 403);

        $this->dispatch('speak', text: $word->example, lang: 'en-US');
    }
}
