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
                ->when($this->search, fn ($query) => $query->where('lang_text', 'like', "%{$this->search}%"))
                ->paginate(10),
        ]);
    }
}
