@props([
    'title',
    'description',
])

<div class="flex w-full flex-col text-center">
    <h1 class="text-xl font-medium">{{ $title }}</h1>
    <p class="text-sm text-gray-500">{{ $description }}</p>
</div>
