<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <x-table.checkout-list class="table-task" name="table" :data="$table">
                </x-table.checkout-list>
                <div class="p-6">
                <x-primary-button class="mt-4"
                                  id="open-module-task-create"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'modal-create-task')"
                >{{ __('Create task') }}</x-primary-button>
                </div>
                <x-modal name="modal-create-task" :show="$errors->userDeletion->isNotEmpty()" focusable>
                    <form id="create-task-form" method="post" action="{{ route('task.create') }}" class="p-6">
                        @csrf

                        <h2 id="module-task-h2" class="text-lg font-medium text-gray-900">
                            {{ __('Create task') }}
                        </h2>
                        <input id="task-id" type="hidden" value="" name="id">
                        <div class="p-6 text-gray-900">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mt-4">
                                        <x-input-label for="task-title" :value="__('Title')" />
                                        <x-text-input id="task-title" class="block mt-1 w-full" type="text" name="title" required />
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="mt-4">
                                        <x-input-label for="task-description" :value="__('Description')" />
                                        <textarea id="task-description" class="block mt-1 w-full" name="description"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="mt-4">
                                        <x-input-label for="task-date" :value="__('Date')" />
                                        <x-text-input id="task-date" class="block mt-1 w-full" type="date" name="date" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <x-secondary-button id="modal-button-close" type="button" x-on:click="$dispatch('close')">
                                {{ __('Cancel') }}
                            </x-secondary-button>

                            <x-primary-button id="submit-button" class="ms-3" x-on:click="$dispatch('close')">
                                {{ __('Create task') }}
                            </x-primary-button>
                        </div>
                    </form>
                </x-modal>
                <script type="module">

                </script>

                @vite([ 'resources/js/taskTable.js', 'resources/js/chanelTask.js',])
            </div>
        </div>
    </div>
    <x-loader/>
</x-app-layout>
