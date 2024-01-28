@if (session()->has('message'))
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal">
        <div class="flex">
            <div class="py-1">
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        </div>
    </div>
@endif
