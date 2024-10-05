@error('error')
    <div class="bg-red-100 border-t-4 border-red-500 rounded-b text-red">
        <div class="flex">
            <div class="py-1">
                <p class="text-sm pl-4">{{ $message }}</p>
            </div>
        </div>
    </div>
@enderror