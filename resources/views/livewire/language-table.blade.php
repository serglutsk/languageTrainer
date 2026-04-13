<div>
    <div class="mb-6 flex justify-between items-center">
        <flux:heading size="xl">{{ __('Languages') }}</flux:heading>

        <div class="flex gap-2">
            <flux:input wire:model.live.debounce.300ms="search" icon="magnifying-glass" placeholder="{{ __('Find by name...') }}" />

            <flux:button variant="primary" icon="plus" wire:click="create">{{ __('Add') }}</flux:button>
        </div>
    </div>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>{{ __('Name') }}</flux:table.column>
            <flux:table.column>{{ __('Code') }}</flux:table.column>
            <flux:table.column align="end">{{ __('Actions') }}</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @foreach ($languages as $language)
                <flux:table.row :key="$language->id">
                    <flux:table.cell>{{ $language->name }}</flux:table.cell>
                    <flux:table.cell>{{ $language->code }}</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:button variant="ghost" icon="pencil-square" size="sm" wire:click="edit({{ $language->id }})" />
                       <flux:button variant="ghost" color="danger" icon="trash" size="sm" wire:click="remove({{ $language->id }})" wire:confirm="{{ __('Are you sure you want to delete this language?')}}" />
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>

    <!-- pop-up -->
    <flux:modal name="language-modal" class="md:w-[400px]">
        <form wire:submit="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $form->language ? __('Edit Language') : __('New Language') }}</flux:heading>
                <flux:subheading>{{ __('Fill in the details below.') }}</flux:subheading>
            </div>

            <flux:input label="{{ __('Name') }}" wire:model="form.name" />
            <flux:input label="{{ __('Code') }}" wire:model="form.code" />

            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <div class="mt-4">{{ $languages->links() }}</div>
</div>
