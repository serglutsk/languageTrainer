<div>
    <div class="mb-6 flex justify-between items-center">
        <flux:heading size="xl">{{ __('My dictionary') }}</flux:heading>

        <div class="flex gap-2">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('Find by value...') }}" />

            <flux:button variant="primary" icon="plus" wire:click="create">{{ __('Add') }}</flux:button>
        </div>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Value') }}</flux:table.column>
            <flux:table.column>{{ __('Translation') }}</flux:table.column>
            <flux:table.column>{{ __('Status') }}</flux:table.column>
            <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($words as $word)
                <flux:table.row :key="$word->id">
                    <flux:table.cell>{{ $word->lang_text }}</flux:table.cell>
                    <flux:table.cell>{{ $word->translation }}</flux:table.cell>
                    <flux:table.cell>{{ $word->status }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button variant="ghost" icon="pencil-square" size="sm" wire:click="edit({{ $word->id }})" />
                       <flux:button variant="ghost" color="danger" icon="trash" size="sm" wire:click="remove({{ $word->id }})" wire:confirm="{{ __('Are you sure you want to delete this $word?')}}" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <!-- pop-up -->
    <flux:modal name="word-modal" class="md:w-[400px]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $form->lang_text ? __('Edit Word') : __('New Word') }}</flux:heading>
                <flux:subheading>{{ __('Fill in the details below.') }}</flux:subheading>
            </div>

            <flux:input label="{{ __('Value') }}" wire:model="form.lang_text" />
            <flux:input label="{{ __('Translation') }}" wire:model="form.translation" />
            <flux:input label="{{ __('Example') }}" wire:model="form.example" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="mt-4">{{ $words->links() }}</div>
</div>
