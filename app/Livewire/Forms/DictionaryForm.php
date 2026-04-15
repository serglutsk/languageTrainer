<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Dictionary;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class DictionaryForm extends Form
{
    public ?Dictionary $dictionary = null;

    #[Validate('required|min:2')]
    public string $lang_text = '';

    #[Validate('required|min:2')]
    public string $translation = '';

    #[Validate('nullable|max:1000')]
    public ?string $example = null;

    public function setDictionary(Dictionary $dictionary): void
    {
        $this->dictionary = $dictionary;
        $this->lang_text = $dictionary->lang_text;
        $this->translation = $dictionary->translation;
        $this->example = $dictionary->example;
    }

    public function store()
    {
        $this->validate();

        $user = auth()->user();
        $languageId = $user?->language_id;

        if ($languageId === null) {
            throw ValidationException::withMessages([
                'language_id' => __('Please select your learning language in settings.'),
            ]);
        }

        Dictionary::create([
            'user_id' => auth()->id(),
            'language_id' => $languageId,
            'lang_text' => $this->lang_text,
            'translation' => $this->translation,
            'example' => $this->example,
        ]);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->dictionary->update([
            'lang_text' => $this->lang_text,
            'translation' => $this->translation,
            'example' => $this->example,
        ]);
        $this->reset();
    }
}
