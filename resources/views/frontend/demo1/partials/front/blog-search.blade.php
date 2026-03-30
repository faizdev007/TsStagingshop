<form action="{{ url('/blog/search-result') }}" method="POST">
    @csrf

    <div class="blog-search">
        <input
            type="text"
            name="keyword"
            placeholder="Search"
            class="form-control"
        >
        <button type="submit" class="btn-send">
            <i class="fa fa-search"></i>
        </button>
    </div>
</form>