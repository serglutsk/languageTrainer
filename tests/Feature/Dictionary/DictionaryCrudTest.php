<?php

declare(strict_types=1);

use App\Models\Dictionary;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('user can create a word', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create(['language_id' => $language->id]);

    $this->actingAs($user);

    Livewire::test('dictionary-table')
        ->call('create')
        ->set('form.lang_text', 'hello')
        ->set('form.translation', 'привіт')
        ->set('form.example', 'Hello, world!')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('dictionaries', [
        'user_id' => $user->id,
        'language_id' => $language->id,
        'lang_text' => 'hello',
        'translation' => 'привіт',
        'example' => 'Hello, world!',
    ]);
});

test('user can update a word', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create(['language_id' => $language->id]);
    $word = Dictionary::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
        'lang_text' => 'hello',
        'translation' => 'привіт',
    ]);

    $this->actingAs($user);

    Livewire::test('dictionary-table')
        ->call('edit', $word->id)
        ->set('form.translation', 'вітаю')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('dictionaries', [
        'id' => $word->id,
        'translation' => 'вітаю',
    ]);
});

test('user can delete a word', function () {
    $language = Language::factory()->create();
    $user = User::factory()->create(['language_id' => $language->id]);
    $word = Dictionary::factory()->create([
        'user_id' => $user->id,
        'language_id' => $language->id,
    ]);

    $this->actingAs($user);

    Livewire::test('dictionary-table')
        ->call('remove', $word->id)
        ->assertHasNoErrors();

    $this->assertDatabaseMissing('dictionaries', [
        'id' => $word->id,
    ]);
});

test('user cannot edit another users word', function () {
    $language = Language::factory()->create();

    $owner = User::factory()->create(['language_id' => $language->id]);
    $intruder = User::factory()->create(['language_id' => $language->id]);

    $word = Dictionary::factory()->create([
        'user_id' => $owner->id,
        'language_id' => $language->id,
    ]);

    $this->actingAs($intruder);

    Livewire::test('dictionary-table')
        ->call('edit', $word->id)
        ->assertForbidden();
});

test('user cannot create a word without selected language', function () {
    $user = User::factory()->create(['language_id' => null]);

    $this->actingAs($user);

    Livewire::test('dictionary-table')
        ->call('create')
        ->set('form.lang_text', 'hello')
        ->set('form.translation', 'привіт')
        ->call('save')
        ->assertHasErrors();
});
