<div>
    <x-modal wire:model="show" maxWidth="sm">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4">Add Prescription Type</h2>

            <div class="space-y-3">
                @foreach($types as $type)
                    <button 
                        wire:click="selectType('{{ $type }}')"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 transition">
                        {{ $type }}
                    </button>
                @endforeach
            </div>
        </div>
    </x-modal>
</div>
