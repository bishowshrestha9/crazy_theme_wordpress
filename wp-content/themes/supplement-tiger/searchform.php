<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text">Search for:</label>
    <input type="search" id="search-field" class="search-field" placeholder="Search articles..."
        value="<?php echo get_search_query(); ?>" name="s" />
    <button type="submit" class="search-submit">Search</button>
</form>