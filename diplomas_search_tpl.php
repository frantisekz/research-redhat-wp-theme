<form class="search" action="<?php echo home_url( '/' ); ?>">
        <input type="search" value="<?php echo esc_html( get_search_query() ); ?>" size="20" name="s" placeholder="Title/Tag/Description....">
        <button type="submit" class="btn btn-white btn-default">Search</button>
		<input type="hidden" name="post_type" value="diplomas">
</form>
