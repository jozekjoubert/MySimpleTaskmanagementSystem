<div x-show="isOpen" 
     @click.away="isOpen = false" 
     @keydown.escape.window="isOpen = false"
     style="display: none;" 
     class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded shadow-lg max-w-lg w-full p-6">
        <h2 class="text-lg font-bold">Edit Task</h2>
        <div>
            {{ $slot }}
        </div>
        <div class="mt-4">
            <button @click="isOpen = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded">Close</button>
        </div>
    </div>
</div>
