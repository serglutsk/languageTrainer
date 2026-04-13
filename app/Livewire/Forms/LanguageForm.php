<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\Language;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LanguageForm extends Form
{
    public ?Language $language = null;

    #[Validate('required|min:2')]
    public $name = '';

    #[Validate('required|max:5')]
    public $code = '';

    public function setLanguage(Language $language)
    {
        $this->language = $language;
        $this->name = $language->name;
        $this->code = $language->code;
    }

    public function store()
    {
        $this->validate();
        Language::create($this->all());
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->language->update($this->all());
        $this->reset();
    }
}
