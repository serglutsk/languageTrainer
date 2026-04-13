<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Livewire\Forms\LanguageForm;
use App\Models\Language;
use Livewire\Component;
use Livewire\WithPagination;

class LanguageTable extends Component
{
    use WithPagination;

    public LanguageForm $form;

    protected $paginationTheme = 'bootstrap';

    #[Url(history: true)]
    public $search = '';

    public function render()
    {
        return view('livewire.language-table', [
            'languages' => Language::query()
                ->when($this->search, fn ($query) => $query->where('name', 'like', "%{$this->search}%"))
                ->paginate(10),
        ]);
    }

    public function create(): void
    {
        $this->form->reset();
        $this->dispatch('modal-show', name: 'language-modal'); // open Flux modal
    }

    public function edit(Language $language): void
    {
        $this->form->setLanguage($language);
        $this->dispatch('modal-show', name: 'language-modal');
    }

    public function remove(Language $language): void
    {
        $language->delete();
    }

    public function save(): void
    {
        $this->form->language ? $this->form->update() : $this->form->store();

        $this->dispatch('modal-close', name: 'language-modal');
    }
}
