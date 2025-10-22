<div>
    <x-modal wire:model="show" maxWidth="lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Select Eye Drops</h2>

            <input
                type="text"
                wire:model.live="search"
                placeholder="Search for an eye drop..."
                class="w-full mb-4 border-gray-300 rounded shadow-sm"
            />

            <div class="space-y-2 max-h-60 overflow-y-auto">
                @foreach($availableDrops as $drop)
                    <div 
                        wire:click="selectDrop({{ $drop['id'] }})"
                        class="cursor-pointer p-2 border rounded hover:bg-blue-100">
                        {{ $drop['name'] }}
                    </div>
                @endforeach
            </div>

            @if($selectedDrops)
                <div class="mt-4">
                    <h3 class="font-semibold mb-2">Selected:</h3>
                    <ul class="list-disc ml-5">
                        @foreach($selectedDrops as $drop)
                            <li>{{ $drop['name'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mt-6 text-right">
                <x-button wire:click="save" class="bg-blue-600 hover:bg-blue-700">Add</x-button>
            </div>
        </div>
    </x-modal>
</div>

