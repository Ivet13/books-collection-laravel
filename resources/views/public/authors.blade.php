  @foreach ($authors as $author)
      <a href="{{ route('author', $author->sitemap->slug) }}" class="author-card">
          <div class="author-card">
              <p>{{ $author->name }}</p>
          </div>
      </a>
  @endforeach
