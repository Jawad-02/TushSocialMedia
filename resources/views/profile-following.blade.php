<x-profile :sharedData="$sharedData">
    <div class="list-group mb-5">
  
      @foreach ($following as $follow)
      <a href="/profile/{{$follow->userBingFollowed->username}}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{$follow->userBingFollowed->avatar}}" />
        {{$follow->userBingFollowed->username}}
      </a>
      @endforeach
      
    </div>
  </x-profile>