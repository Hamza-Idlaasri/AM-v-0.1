
<nav class="navbar navbar-light bg-light float-right">
    <form class="form-inline" action="{{ route($route) }}" method="GET">
      <input id="search" style="border-bottom-right-radius: 0;border-top-right-radius: 0" class="form-control" type="search" name="search" placeholder="Search" aria-label="Search" value="{{ request()->query('search') }}">
      <button style="border-bottom-left-radius: 0;border-top-left-radius: 0" class="btn btn-primary" type="submit">
        <i class="fas fa-search"></i>
      </button>
    </form>
</nav>

