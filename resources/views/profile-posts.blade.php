<x-profile :sharedData="$sharedData">
  <div class="list-group mb-5">

    @foreach ($posts as $post)
    <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action">
      <img class="avatar-tiny" src="{{$post->user->avatar}}" />
      <strong>{{$post->title}}</strong> on {{$post->created_at->format('j/n/Y')}}
    </a>
    @endforeach
    
  </div>
</x-profile>