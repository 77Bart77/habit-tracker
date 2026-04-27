@props([
    'goal',
    'likedGoalIds' => [],
    'showCount' => true,
])

@php
    $liked = in_array($goal->id, $likedGoalIds);
@endphp

<form method="POST"
      action="{{ route('goals.like.toggle', $goal->id) }}"
      class="d-inline">
    @csrf

    <button type="submit"
        class="pixel-like-btn {{ $liked ? 'liked' : '' }}"
        title="{{ $liked ? 'Usuń polubienie' : 'Polub cel' }}">

        {{-- PIXEL HEART --}}
        <svg width="18" height="18" viewBox="0 0 16 16" class="pixel-heart">
            <rect x="2" y="1" width="3" height="3"/>
            <rect x="11" y="1" width="3" height="3"/>

            <rect x="1" y="4" width="5" height="3"/>
            <rect x="10" y="4" width="5" height="3"/>

            <rect x="3" y="7" width="10" height="3"/>
            <rect x="5" y="10" width="6" height="3"/>
            <rect x="7" y="13" width="2" height="2"/>
        </svg>

        @if($showCount)
            <span class="like-count">{{ $goal->likes_count }}</span>
        @endif
    </button>
</form>
