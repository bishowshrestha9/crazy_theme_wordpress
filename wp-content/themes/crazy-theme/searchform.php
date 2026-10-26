<?php
/**
 * Custom search form template
 */
?>
<style>
.custom-search-form { display: flex; align-items: stretch; width: 100%; max-width: 500px; margin: 0 auto; background: #ffffff; border: 2px solid #e2e8f0; border-radius: 30px; overflow: hidden; transition: all 0.2s ease; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
.custom-search-form:focus-within { border-color: #0284c7; box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.15); }
.custom-search-form label { flex: 1; display: flex; margin: 0; width: 100%; }
.custom-search-field { width: 100%; border: none; padding: 1rem 1.5rem; font-size: 1rem; color: #1e293b; outline: none; background: transparent; font-family: 'Atkinson Hyperlegible', sans-serif; }
.custom-search-submit { background: #0284c7; color: white; border: none; padding: 0 1.5rem; font-weight: 700; cursor: pointer; transition: background 0.2s; font-size: 1rem; font-family: 'Atkinson Hyperlegible', sans-serif; }
.custom-search-submit:hover { background: #0274b3; }
</style>
<form role="search" method="get" class="custom-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'crazytheme' ); ?></span>
		<input type="search" class="custom-search-field" placeholder="<?php echo esc_attr__( 'Search for supplements...', 'crazytheme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	</label>
	<button type="submit" class="custom-search-submit">
		<?php echo esc_html__( 'Explore', 'crazytheme' ); ?>
	</button>
</form>
