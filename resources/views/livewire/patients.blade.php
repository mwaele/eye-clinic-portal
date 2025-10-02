<div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100">Patients (Last 30 Days)</h2>
        <div class="flex space-x-2">
            <input type="text"
                wire:model.debounce.300ms="search"
                placeholder="Search patients..."
                class="px-3 py-2 m-2 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600 focus:ring focus:ring-indigo-500">
            <button wire:click="openModal"
                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                + Register Patient
            </button>
        </div>
    </div>


    @if (session()->has('message'))
        <div class="mb-4 text-green-600 dark:text-green-400">
            {{ session('message') }}
        </div>
    @endif

    <table class="min-w-full bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
        <thead class="text-md bg-gray-100 dark:bg-gray-700">
            <tr>
                <th class="px-4 py-2 text-left">#</th>
                <th class="px-4 py-2 text-left">Patient No.</th>
                <th class="px-4 py-2 text-left">Name</th>
                <th class="px-4 py-2 text-left">Phone</th>
                <th class="px-4 py-2 text-left">Age</th>
                <th class="px-4 py-2 text-left">Residence</th>
                <th class="px-4 py-2 text-left">Date of Visit</th>
                <th class="px-4 py-2 text-left">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr class="text-sm border-b dark:border-gray-700">
                    <td class="px-4 py-2"> {{ $loop->iteration }}.</td>
                    <td class="px-4 py-2">{{ $patient->patient_no }}</td>
                    <td class="px-4 py-2">{{ $patient->name }}</td>
                    <td class="px-4 py-2">0{{ $patient->phone }}</td>
                    <td class="px-4 py-2">{{ $patient->age }}</td>
                    <td class="px-4 py-2">{{ $patient->address }}</td>
                    <td class="px-4 py-2">
                        {{ optional($patient->visit_date)->format('d M Y') }}
                    </td>
                    <td class="px-4 py-2 flex space-x-2">
                        <button wire:click="edit({{ $patient->id }})"
                            class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                            Edit
                        </button>
                        <button wire:click="checkIn({{ $patient->id }})"
                            class="px-2 py-1 bg-green-600 text-white rounded hover:bg-green-700">
                            Check-in
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-4 text-center text-gray-500">No patients found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $patients->links() }}
    </div>

    <!-- Modal -->
    <x-modal wire:model="showModal" maxWidth="lg">
        <div class="p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Register New Patient</h2>

            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 dark:text-gray-300">First Name</label>
                    <input type="text" wire:model.defer="first_name" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('first_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Last Name</label>
                    <input type="text" wire:model.defer="last_name" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('last_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Phone</label>
                    <input type="text" wire:model.defer="phone" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Age</label>
                    <input type="number" wire:model.defer="age" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                    @error('age') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-gray-700 dark:text-gray-300">Address</label>
                    <input type="text" wire:model.defer="address" class="w-full mt-1 rounded-md border-gray-300 dark:bg-gray-700 dark:border-gray-600" />
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button wire:click="$set('showModal', false)" type="button"
                    class="px-4 py-2 mr-4 p-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                    Cancel
                </button>
                <button wire:click="registerPatient" type="button"
                    class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Save
                </button>
            </div>
        </div>
    </x-modal>
</div>
