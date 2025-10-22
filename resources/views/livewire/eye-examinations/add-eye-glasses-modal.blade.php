<div>
    <x-modal wire:model="show" maxWidth="2xl">
        <div class="p-6 space-y-6">
            <h2 class="text-lg font-semibold">Add Eye Glasses Prescription</h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h3 class="font-semibold mb-2">Right Eye (R.E)</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <x-input label="D.S" wire:model="re_ds" />
                        <x-input label="Cyl" wire:model="re_cyl" />
                        <x-input label="Axis" wire:model="re_axis" />
                        <x-input label="Add" wire:model="re_add" />
                    </div>
                </div>

                <div>
                    <h3 class="font-semibold mb-2">Left Eye (L.E)</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <x-input label="D.S" wire:model="le_ds" />
                        <x-input label="Cyl" wire:model="le_cyl" />
                        <x-input label="Axis" wire:model="le_axis" />
                        <x-input label="Add" wire:model="le_add" />
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <x-input label="P.D" wire:model="pd" />
                <x-input label="N.P.D" wire:model="npd" />
            </div>

            <div>
                <label for="specs">Other Qualities / Specifications</label>
                <textarea id="specs" wire:model="specs" class="form-input rounded-md shadow-sm mt-1 block w-full"></textarea>
            </div>


            <div class="text-right">
                <x-button wire:click="save" class="bg-blue-600 hover:bg-blue-700">Add</x-button>
            </div>
        </div>
    </x-modal>
</div>
