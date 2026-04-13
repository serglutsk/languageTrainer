<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Dictionary;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DictionaryForm extends Form
{
    public ?Dictionary $dictionary = null;

    #[Validate('required|min:2')]
    public string $lang_text = '';

    #[Validate('required|min:2')]
    public string $translation = '';

    public function setDictionary(Dictionary $dictionary): void
    {
        $this->dictionary = $dictionary;
        $this->lang_text = $dictionary->lang_text;
        $this->translation = $dictionary->translation;
    }

    public function store()
    {
        $this->validate();

        Dictionary::create([
            'user_id' => auth()->id(),
            'lang_text' => $this->lang_text,
            'translation' => $this->translation,
        ]);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->dictionary->update($this->all());
        $this->reset();
    }
}
