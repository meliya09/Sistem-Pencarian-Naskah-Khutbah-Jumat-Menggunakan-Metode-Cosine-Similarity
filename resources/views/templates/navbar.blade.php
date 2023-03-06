<nav class="navbar navbar-expand-sm bg-dark navbar-dark">
    <!-- Brand/logo -->
    <a class="navbar-brand" href="{{ route('cosinesimilarity') }}">Cosine Similarity</a>

    <!-- Links -->
    <ul class="navbar-nav">
        @auth
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cosinesimilarity') }}">Cosine Similarity</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('documents.index') }}">Document</a>
            </li>
            <li class="nav-item">
                <form class="btn btn-sm" action="{{ route('logout') }}" method="post">
                    @method('POST')
                    @csrf
                    <button type="submit"class="btn-sm btn-danger">
                        Logout
                    </button>
                </form>
            </li>
      @endauth
    </ul>
</nav>
