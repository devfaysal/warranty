<div class="bg-white">
    <div class="px-6 py-24 sm:px-6 sm:py-32 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Register your purchased product</h2>
            <p class="mx-auto mt-6 max-w-xl text-lg leading-8 text-gray-600">Register product to get online warranty and surprise gift</p>
            <div class="mt-10 flex items-center justify-start gap-x-6">
                <form wire:submit="create" class="w-full">
                    {{ $this->form }}
                    <button type="submit" class="mt-5 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Submit
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>