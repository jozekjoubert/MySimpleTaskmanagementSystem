<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <button id="accordion-button" class="w-full text-left flex justify-between items-center bg-gray-200 dark:bg-gray-700 px-4 py-2 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <span>Create New Task</span>
                    <svg id="accordion-icon" class="w-4 h-4 transition-transform duration-200 transform" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15l-3-3m0 0l3-3m-3 3h12" />
                    </svg>
                </button>

                <div id="accordion-content" class="hidden mt-4">
                    <form id="create-task-form" action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <div class="py-2">
                            <label for="title">Title</label>
                            <input type="text" id="title" name="title" required class="w-full border border-gray-300 dark:bg-gray-700 rounded">
                        </div>
                        <div class="py-2">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" required class="w-full border border-gray-300 dark:bg-gray-700 rounded"></textarea>
                        </div>
                        <div class="py-2">
                            <label for="assignedto">Assigned To:</label>
                            <select id="assignedto" name="assignedto" required class="mx-4 h-10 text-center border border-gray-300 dark:bg-gray-700 w-1/3 rounded">
                                <option value="" disabled selected>Select a user</option>
                                @foreach($users as $user)
                                    <option class="text-center" value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="py-2">
                            <label for="due_date">Due Date: </label>
                            <input type="date" id="due_date" name="due_date" required class="text-center mx-8 border border-gray-300 dark:bg-gray-700 w-1/4 rounded">
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 border-gray-300 dark:bg-gray-700 text-white px-4 py-2 rounded">Create Task</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


    <div x-data="taskFiltering()" x-init="init()">
        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 gap-4"> 
                            <div class="flex items-center font-bold">
                                <div class="w-full">Task Title</div>
                                <div class="w-full">Task Description</div>
                                <div class="w-full">Task Status</div>
                                <div class="w-full">Due Date</div>
                                <div class="w-full">Assigned To</div>
                                <div class="w-full">Action</div>
                            </div>

                            <div class="p-6 text-gray-900 dark:text-gray-100">


                            <form method="GET" action="{{ route('dashboard') }}" class="mb-4 flex gap-4 ">
                                <select name="status" class="form-select border border-gray-300 dark:bg-gray-700 rounded">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>

                                <input type="date" name="due_date" value="{{ request('due_date') }}" class="form-input border border-gray-300 dark:bg-gray-700 rounded" />

                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search tasks..." class="form-input border border-gray-300 dark:bg-gray-700 rounded" />

                                <button type="submit" class="btn btn-outline-primary bg-blue-500 text-white px-4 py-2 rounded">Filter/Search</button>
                            </form>

                            </div>

                            @if($tasks->isEmpty())
                                <div>No tasks match the filter criteria.</div>
                            @else
                                @foreach($tasks as $task)
                                    <div class="flex items-center">
                                        <div class="w-full">{{ $task->title }}</div>
                                        <div class="w-full">{{ $task->description }}</div>
                                        <div class="w-full">{{ $task->status }}</div>
                                        <div class="w-full">{{ $task->due_date }}</div>
                                        <div class="w-full">{{ $task->user->name }}</div>
                                        <div class="w-full">
                                            <button @click="openModal({ 
                                                id: {{ $task->id }},
                                                title: '{{ $task->title }}',
                                                description: '{{ $task->description }}',
                                                status: '{{ $task->status }}',
                                                due_date: '{{ $task->due_date }}',
                                                user: '{{ $task->user->name }}'
                                            })" 
                                            class="btn btn-outline-primary bg-blue-500 text-white px-4 py-2 rounded">
                                                Edit
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif


                        </div>
                    </div>
                </div>
            </div>

            <div x-show="showModal" class="fixed inset-0 flex justify-center items-center">
                <div class="bg-gray-800 p-4 border-solid border-[1px] border-gray-200 rounded">
                    <h3 class="text-lg text-gray-200 font-bold mb-4">Edit Task</h3>
                    <form method="POST" :action="'/tasks/' + task.id">
                        @csrf
                        @method('POST') 
                        
                        <div class="mt-4">
                            <label for ="title" class="text-gray-800">Task Title</label>
                            <input type="text" id="title" class="border p-2 w-full" name="title" x-model="task.title">
                        </div>

                        <div class="mt-4">
                            <label for="description" class="text-gray-800">Task Description</label>
                            <textarea id="description" class="border p-2 w-full" name="description" x-model="task.description"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="status" class="text-gray-800">Task Status</label>
                            <input type="text" id="status" class="border p-2 w-full" name="status" x-model="task.status">
                        </div>

                        <div class="mt-4">
                            <label for="due_date" class="text-gray-800">Due Date</label>
                            <input type="date" id="due_date" class="border p-2 w-full" name="due_date" x-model="task.due_date">
                        </div>

                        <div class="mt-4">
                            <label for="user_id" class="text-gray-800">Assigned User</label>
                            <select id="user_id" name="user_id" class="border p-2 w-full">
                                <option value="" disabled selected>Select a user</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" x-bind:selected="task.user_id == {{ $user ->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end ">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save Changes</button>
                            <button type="button" @click="showModal = false" class="ml-2 bg-gray-500 text-white px-4 py-2 rounded">Close</button>
                        </div>
                    </form>
                    <div>
                    <div class="mt-6">
    <h3 class="text-gray-300">Activity Logs</h3>
    <ul>
        @foreach($activityLogs as $log)  {{-- Assuming $activityLogs is passed from the controller --}}
            <li class="text-gray-300">
                <strong>{{ $log->action }}</strong> - 
                <small>{{ $log->created_at->format('Y-m-d H:i:s') }}</small>
            </li>
        @endforeach

        @if($activityLogs->isEmpty())
            <li class="text-gray-300">No activity logs available.</li>
        @endif
    </ul>
</div>
                    </div>
        <div class="mt-4 flex justify-end">
        <div class="mt-4 flex justify-end">
            <button @click="deleteTask(task.id)" class="bg-red-500 text-white px-4 py-2 rounded">Delete Task</button>
        </div>
        </div>
                </div>
            </div>

        </div>


    </div>
<script>
function taskFiltering() {
    return {
        tasks: @json($tasks),   

        showModal: false,       

        openModal(task) {
            this.task = { ...task };
            this.showModal = true;
        }
    };
}
</script>

<script>
    document.getElementById('accordion-button').addEventListener('click', function() {
        const content = document.getElementById('accordion-content');
        const icon = document.getElementById('accordion-icon');

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    });

    function deleteTask(taskId) {
        if (confirm('Are you sure you want to delete this task?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/tasks/${taskId}`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}'; 

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE'; 

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>
</x-app-layout>
