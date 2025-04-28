<?php
/**
 * Twenty Twenty-Five functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Five
 * @since Twenty Twenty-Five 1.0
 */

// Adds theme support for post formats.
if (!function_exists('twentytwentyfive_post_format_setup')):
	/**
	 * Adds theme support for post formats.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_post_format_setup()
	{
		add_theme_support('post-formats', array('aside', 'audio', 'chat', 'gallery', 'image', 'link', 'quote', 'status', 'video'));
	}
endif;
add_action('after_setup_theme', 'twentytwentyfive_post_format_setup');

// Enqueues editor-style.css in the editors.
if (!function_exists('twentytwentyfive_editor_style')):
	/**
	 * Enqueues editor-style.css in the editors.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_editor_style()
	{
		add_editor_style(get_parent_theme_file_uri('assets/css/editor-style.css'));
	}
endif;
add_action('after_setup_theme', 'twentytwentyfive_editor_style');

// Enqueues style.css on the front.
if (!function_exists('twentytwentyfive_enqueue_styles')):
	/**
	 * Enqueues style.css on the front.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_enqueue_styles()
	{
		wp_enqueue_style(
			'twentytwentyfive-style',
			get_parent_theme_file_uri('style.css'),
			array(),
			wp_get_theme()->get('Version')
		);
	}
endif;
add_action('wp_enqueue_scripts', 'twentytwentyfive_enqueue_styles');

// Registers custom block styles.
if (!function_exists('twentytwentyfive_block_styles')):
	/**
	 * Registers custom block styles.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_block_styles()
	{
		register_block_style(
			'core/list',
			array(
				'name' => 'checkmark-list',
				'label' => __('Checkmark', 'twentytwentyfive'),
				'inline_style' => '
				ul.is-style-checkmark-list {
					list-style-type: "\2713";
				}

				ul.is-style-checkmark-list li {
					padding-inline-start: 1ch;
				}',
			)
		);
	}
endif;
add_action('init', 'twentytwentyfive_block_styles');

// Registers pattern categories.
if (!function_exists('twentytwentyfive_pattern_categories')):
	/**
	 * Registers pattern categories.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_pattern_categories()
	{

		register_block_pattern_category(
			'twentytwentyfive_page',
			array(
				'label' => __('Pages', 'twentytwentyfive'),
				'description' => __('A collection of full page layouts.', 'twentytwentyfive'),
			)
		);

		register_block_pattern_category(
			'twentytwentyfive_post-format',
			array(
				'label' => __('Post formats', 'twentytwentyfive'),
				'description' => __('A collection of post format patterns.', 'twentytwentyfive'),
			)
		);
	}
endif;
add_action('init', 'twentytwentyfive_pattern_categories');

// Registers block binding sources.
if (!function_exists('twentytwentyfive_register_block_bindings')):
	/**
	 * Registers the post format block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return void
	 */
	function twentytwentyfive_register_block_bindings()
	{
		register_block_bindings_source(
			'twentytwentyfive/format',
			array(
				'label' => _x('Post format name', 'Label for the block binding placeholder in the editor', 'twentytwentyfive'),
				'get_value_callback' => 'twentytwentyfive_format_binding',
			)
		);
	}
endif;
add_action('init', 'twentytwentyfive_register_block_bindings');

add_action('rest_api_init', function () {
	register_rest_route('custom/v1', '/health', [
		'methods' => 'GET',
		'callback' => fn() => new WP_REST_Response(['status' => 'ok'], 200),
		'permission_callback' => '__return_true',
	]);
});

add_action('save_post', 'notify_clearing_cache_on_post_save', 10, 3);

function notify_clearing_cache_on_post_save($post_ID, $post, $update)
{
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		return;
	if ($post->post_type !== 'post')
		return;
	if (!defined('GG_API_BASE_URL') || !defined('GG_API_KEY'))
		return;

	$endpoint = rtrim(GG_API_BASE_URL, '/') . '/v1/cache/keys';

	$categories = get_the_category($post_ID);
	if (!empty($categories) && !is_wp_error($categories)) {
		foreach ($categories as $category) {
			$category_slug = $category->slug;
			$pattern_category = urlencode("posts:category:$category_slug*");
			$url_category = $endpoint . "?pattern=$pattern_category";

			wp_remote_request($url_category, [
				'method' => 'DELETE',
				'timeout' => 5,
				'blocking' => false,
				'headers' => [
					'accept' => '*/*',
					'x-api-key' => GG_API_KEY,
				],
			]);
		}
	}

	$post_slug = $post->post_name;
	$pattern_post = urlencode("posts:$post_slug");
	$url_post = $endpoint . "?pattern=$pattern_post";

	wp_remote_request($url_post, [
		'method' => 'DELETE',
		'timeout' => 5,
		'blocking' => false,
		'headers' => [
			'accept' => '*/*',
			'x-api-key' => GG_API_KEY,
		],
	]);

	$tags = get_the_tags($post_ID);
	if (!empty($tags) && !is_wp_error($tags)) {
		foreach ($tags as $tag) {
			$tag_slug = $tag->slug;
			$pattern_tag = urlencode("posts:tag:$tag_slug*");
			$url_tag = $endpoint . "?pattern=$pattern_tag";

			wp_remote_request($url_tag, [
				'method' => 'DELETE',
				'timeout' => 5,
				'blocking' => false,
				'headers' => [
					'accept' => '*/*',
					'x-api-key' => GG_API_KEY,
				],
			]);
		}
	}
}

add_action('edited_category', 'notify_clearing_cache_on_category_edit', 10, 2);

function notify_clearing_cache_on_category_edit($term_id, $tt_id)
{
	if (!defined('GG_API_BASE_URL') || !defined('GG_API_KEY'))
		return;

	$endpoint = rtrim(GG_API_BASE_URL, '/') . '/v1/cache/keys';

	$category = get_term($term_id, 'category');
	if (is_wp_error($category) || !$category)
		return;

	$category_slug = $category->slug;

	$general_url = $endpoint . '/categories';
	wp_remote_request($general_url, [
		'method' => 'DELETE',
		'timeout' => 5,
		'blocking' => false,
		'headers' => [
			'accept' => '*/*',
			'x-api-key' => GG_API_KEY,
		],
	]);

	$slug_encoded = urlencode("categories:$category_slug");
	$specific_url = $endpoint . "/$slug_encoded";
	wp_remote_request($specific_url, [
		'method' => 'DELETE',
		'timeout' => 5,
		'blocking' => false,
		'headers' => [
			'accept' => '*/*',
			'x-api-key' => GG_API_KEY,
		],
	]);
}

add_action('edited_post_tag', 'notify_clearing_cache_on_tag_edit', 10, 2);

function notify_clearing_cache_on_tag_edit($term_id, $tt_id)
{
	if (!defined('GG_API_BASE_URL') || !defined('GG_API_KEY'))
		return;

	$endpoint = rtrim(GG_API_BASE_URL, '/') . '/v1/cache/keys';

	$tag = get_term($term_id, 'post_tag');
	if (is_wp_error($tag) || !$tag)
		return;

	$tag_slug = $tag->slug;

	$general_url = $endpoint . '/tags';
	wp_remote_request($general_url, [
		'method' => 'DELETE',
		'timeout' => 5,
		'blocking' => false,
		'headers' => [
			'accept' => '*/*',
			'x-api-key' => GG_API_KEY,
		],
	]);

	$slug_encoded = urlencode("tags:$tag_slug");
	$specific_url = $endpoint . "/$slug_encoded";
	wp_remote_request($specific_url, [
		'method' => 'DELETE',
		'timeout' => 5,
		'blocking' => false,
		'headers' => [
			'accept' => '*/*',
			'x-api-key' => GG_API_KEY,
		],
	]);
}

function pre_select_related_posts_by_category($field)
{
	global $post;
	if (!$post) {
		return $field;
	}

	$categories = get_the_category($post->ID);
	if (empty($categories)) {
		return $field;
	}

	$category_id = $categories[0]->term_id;

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
		'post__not_in' => array($post->ID),
		'orderby' => 'date',
		'order' => 'DESC',
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => $category_id,
			),
		),
	);

	$recent_posts = get_posts($args);

	if (!empty($recent_posts)) {
		$field['default_value'] = wp_list_pluck($recent_posts, 'ID');
	}

	return $field;
}
add_filter('acf/load_field/name=related_posts', 'pre_select_related_posts_by_category');

// Auto-fill the ACF field when saving the post, only if the field is empty
function auto_fill_related_posts_on_save($post_id, $post, $update)
{
	// Prevent autosave and revisions
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	if (wp_is_post_revision($post_id)) {
		return;
	}

	// Only apply to posts
	if ($post->post_type !== 'post') {
		return;
	}

	// Check if the ACF field already has a value
	$existing_value = get_field('related_posts', $post_id);
	if (!empty($existing_value)) {
		// Do not overwrite if user has manually selected related posts
		return;
	}

	$categories = get_the_category($post_id);
	if (empty($categories)) {
		return;
	}

	$category_id = $categories[0]->term_id;

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => 3,
		'post__not_in' => array($post_id),
		'orderby' => 'date',
		'order' => 'DESC',
		'tax_query' => array(
			array(
				'taxonomy' => 'category',
				'field' => 'term_id',
				'terms' => $category_id,
			),
		),
	);

	$related_posts = get_posts($args);

	if (!empty($related_posts)) {
		$related_ids = wp_list_pluck($related_posts, 'ID');
		update_field('related_posts', $related_ids, $post_id);
	}
}
add_action('save_post', 'auto_fill_related_posts_on_save', 20, 3);

// Registers block binding callback function for the post format name.
if (!function_exists('twentytwentyfive_format_binding')):
	/**
	 * Callback function for the post format name block binding source.
	 *
	 * @since Twenty Twenty-Five 1.0
	 *
	 * @return string|void Post format name, or nothing if the format is 'standard'.
	 */
	function twentytwentyfive_format_binding()
	{
		$post_format_slug = get_post_format();

		if ($post_format_slug && 'standard' !== $post_format_slug) {
			return get_post_format_string($post_format_slug);
		}
	}
endif;
