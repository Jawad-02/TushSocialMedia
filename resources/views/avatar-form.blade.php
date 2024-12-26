<x-layout>
    <div class="container container--narrow py-md-5">
        <h2 class="text-center mb-3">Uplode a New Avatar</h2>
        <form action="/manage-avatar" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <input type="file" name="avatar" >
                @error('avatar')
                    <p class="alert small alert-danger shadow-sm">{{$message}}</p>
                @enderror
            </div>
            <button class="btn btns mb-5">Save</button>
        </form>
    </div>
</x-layout>