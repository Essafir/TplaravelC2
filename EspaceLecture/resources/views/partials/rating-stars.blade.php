<div class="rating-stars">
    @for($i = 1; $i <= 5; $i++)
        @if($i <= $rating)
            <i class="fas fa-star text-warning"></i>
        @elseif($i - 0.5 <= $rating)
            <i class="fas fa-star-half-alt text-warning"></i>
        @else
            <i class="far fa-star text-warning"></i>
        @endif
    @endfor
</div>