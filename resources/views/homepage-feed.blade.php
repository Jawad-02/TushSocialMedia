<x-layout>
    <div class="container py-md-5  container--narrow">
      @unless ($posts->isEmpty())
          <h2 class="text-center mb-4">The Latest From Those You Follow</h2> 
          <div class="list-group mb-5">

            @foreach ($posts as $post)
            <a href="/post/{{$post->id}}" class="list-group-item list-group-item-action mb-3">
              <img class="avatar-tiny" src="{{$post->user->avatar}}" />
              <strong>{{$post->title}}</strong> <span class="text-muted small">By {{$post->user->username}} on {{$post->created_at->format('j/n/Y')}}</span>
              <div class="text-center border m-2 p-2">{{$post->body}}</div>
            </a>
            @endforeach
            
          </div> 
          
        @else      
        <div class="text-center">
          <h2>Hello <strong>{{auth()->user()->username}}</strong>, your feed is empty.</h2>
          <p class="lead text-muted">Your feed displays the latest posts from the people you follow. If you don&rsquo;t have any friends to follow that&rsquo;s okay; you can use the &ldquo;Search&rdquo; feature in the top menu bar to find content written by people with similar interests and then follow them.</p>
        </div>
      @endunless
      </div>
</x-layout>