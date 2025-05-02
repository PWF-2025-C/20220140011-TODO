<x-app-layout>

    <x-slot name="header">

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit') }}
            
</h2>
</x-slot>

<div class="mt-4 p-6 text-gray-900 dark:text-grey-100 overflow-hidden bg-white shadow-sm sm:rounded-lg
                dark:bg-gray-800">
    <form action="{{ route('todo.update', $todo) }}" method="post" class="">
        @csrf
        @method('patch')
        <div class="mb-6">
            <x-input-label for="title" :value="__('Title')"/>
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('name', $todo->title)" required autofocus autocomplete="title" />
            <x-input-error class="mt-2" :messages="$errors->get('title')" />
        </div>
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            <x-cancel-button href="{{ route('todo.view') }}" />
        </div>
    </form>
</div>

</x-app-layout>