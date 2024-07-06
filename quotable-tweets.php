<?php
/**
 * Plugin Name: Quotable Tweets
 * Plugin URI: https://draftpress.com/products
 * Description: The Quotable Tweets plugin gives you an easy way to add a
 * beautiful actionable tweet link to your sidebar.
 * Version: 1.1.8
 * Author: DraftPress
 * Author URI: https://draftpress.com
 * License: GPL2
 * php version: 7.0
 *
 * @category Plugin
 * @package  QuotableTweets
 * @author   DraftPress <support@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}



/**
 * Global Definitions
 */

// Plugin Name.
if ( ! defined( 'NNROBOTS_QUOTABLE_TWEETS_PLUGIN_NAME' ) ) {
	define(
		'NNROBOTS_QUOTABLE_TWEETS_PLUGIN_NAME',
		trim( dirname( plugin_basename( __FILE__ ) ), '/' )
	);
}

// Plugin directory.
if ( ! defined( 'NNROBOTS_QUOTABLE_TWEETS_PLUGIN_DIR' ) ) {
	define(
		'NNROBOTS_QUOTABLE_TWEETS_PLUGIN_DIR',
		WP_PLUGIN_DIR . '/' . NNROBOTS_QUOTABLE_TWEETS_PLUGIN_NAME
	);
}

// Hooks / Filters.
add_action( 'init', array( 'NNRobots_Quotable_Tweets', 'loadTextDomain' ) );
add_filter(
	'plugin_action_links_' . plugin_basename( __FILE__ ),
	array(
		'NNRobots_Quotable_Tweets',
		'settingsLink',
	)
);

// Register the Widget.
add_action(
	'widgets_init',
	function () {
		register_widget( 'NNRobots_Quotable_Tweets' );
	}
);

add_filter( 'widget_types_to_hide_from_legacy_widget_block', array( 'NNRobots_Quotable_Tweets', 'hide_quotable_tweets_widget' ) );


/**
 * NNRobots_Quotable_Tweets main class
 *
 * @category Class
 * @package  QuotableTweets
 * @author   DraftPress <support@draftpress.com>
 * @license  GNU General Public License 2
 * (https://www.gnu.org/licenses/old-licenses/gpl-2.0.en.html)
 * @link     https://draftpress.com/products
 * @since    1.0.0
 * @using    WordPress 6.5.5
 */
class NNRobots_Quotable_Tweets extends WP_Widget {

	/**
	 * Prefix
	 *
	 * (default value: 'nnrobots_quotable_tweets_')
	 *
	 * @var    string
	 * @access public
	 * @static
	 */

	public static $prefix = 'nnrobots_quotable_tweets_';

	/**
	 * Base ID.
	 *
	 * (default value: 'nnrobots_quotable_tweets')
	 *
	 * @var    string
	 * @access public
	 * @static
	 */
	public static $base_id = 'nnrobots_quotable_tweets';

	/**
	 * Prefix Dash
	 *
	 * (default value: 'nnrobots_related_posts_')
	 *
	 * @var    string
	 * @access private
	 * @static
	 */
	private static $prefix_dash = 'nnr-qt-';

	/**
	 * Load the text domain
	 *
	 * @since  1.0.0
	 * @return void
	 */
	public static function loadTextDomain() {
		$locale = apply_filters(
			'plugin_locale',
			get_locale(),
			'quotable-tweets'
		);

		load_textdomain(
			'quotable-tweets',
			WP_LANG_DIR . '/quotable-tweets/quotable-tweets-' . $locale . '.mo'
		);

		load_plugin_textdomain(
			'quotable-tweets',
			false,
			NNROBOTS_QUOTABLE_TWEETS_PLUGIN_DIR . '/languages/'
		);
	}

	/**
	 * Hooks to 'plugin_action_links_' filter.
	 *
	 * @param array $links An array of plugin action links.
	 *
	 * @since 1.0.0
	 *
	 * @return array The modified array of plugin action links.
	 */
	public static function settingsLink( $links ) {
		$widget_link = '<a href="widgets.php">Widget</a>';
		array_unshift( $links, $widget_link );

		return $links;
	}

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
			'quotable_tweets', // Base ID.
			esc_html__( 'Quotable Tweets', 'quotable-tweets' ),
			array(
				'description' => esc_html__(
					'Beautiful way to display an actionable tweet.',
					'quotable-tweets'
				),
			)
		);

		add_action( 'init', array( $this, 'register_quotable_tweets_block' ) );
	}

	/**
	 * Register the quotable tweets block.
	 *
	 * @return void
	 */
	public function register_quotable_tweets_block() {
		wp_register_script(
			'quotable-tweets-block',
			plugins_url( 'block/build/index.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-block-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/build/index.js' ),
			true
		);

		wp_register_style(
			'quotable-tweets-editor-style',
			plugins_url( 'block/build/editor.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/build/editor.css' )
		);

		wp_register_style(
			'quotable-tweets-block',
			plugins_url( 'block/build/style.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'block/build/style.css' )
		);

		register_block_type(
			'draftpress/quotable-tweets',
			array(
				'editor_script'   => 'quotable-tweets-block',
				'editor_style'    => 'quotable-tweets-editor-style',
				'style'           => 'quotable-tweets-block',
				'render_callback' => array( $this, 'render_quotable_tweets_block' ),
			)
		);
	}


	/**
	 * Render the quotable tweets block.
	 *
	 * @param array  $attributes The block attributes.
	 * @param string $content    The block content.
	 *
	 * @return string The block HTML.
	 */
	public function render_quotable_tweets_block( $attributes, $content ) {
		if ( ! is_singular() ) {
			return '';
		}

		global $post;

		// Default post link.
		$post_link = wp_get_shortlink( $post->ID );
		if ( ! $post_link ) {
			$post_link = get_permalink( $post->ID );
		}

		// Bitly link shortening.
		if ( ! empty( $attributes['bitlyAccessToken'] ) ) {

			$response = wp_remote_get( 'https://api-ssl.bitly.com/v3/shorten?access_token=' . $attributes['bitlyAccessToken'] . '&longUrl=' . rawurlencode( $post_link ) . '&format=json' );

			if ( ! is_wp_error( $response ) ) {
				$body   = wp_remote_retrieve_body( $response );
				$result = json_decode( $body );
				if ( isset( $result->data->url ) ) {
					$post_link = $result->data->url;
				}
			}
		}

		$post_title         = get_the_title( $post );
		$twitter_intent_url = 'https://twitter.com/intent/tweet?text=' . rawurlencode( $post_title . ' ' . $post_link );

		ob_start();
		?>
		<div class="wp-block-draftpress-quotable-tweets">
			<div class="nnr-qt-container">
				<div class="nnr-qt-title-container">
					<span class="nnr-qt-icon-twitter" aria-hidden="true"></span>
					<span class="nnr-qt-title"><?php echo esc_html( $attributes['title'] ); ?></span>
				</div>
				<div class="nnr-qt-text-container">
					<p class="nnr-qt-post-title"><?php echo esc_html( $post_title ); ?></p>
					<p class="nnr-qt-quote-container">
						<span class="nnr-qt-quote dashicons dashicons-format-quote"></span>
					</p>
				</div>
				<a class="nnr-qt-button" href="<?php echo esc_url( $twitter_intent_url ); ?>">
					<?php echo esc_html( $attributes['buttonText'] ); ?>
				</a>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Hide the widget from the block editor
	 *
	 * @param array $widget_types Array of widget types.
	 * @return array
	 */
	public static function hide_quotable_tweets_widget( $widget_types ) {
		$widget_types[] = 'quotable_tweets';
		return $widget_types;
	}

	/**
	 * Front-end display of the widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from the database.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @return true
	 */
	public function widget( $args, $instance ) {
		// Do not display if not a single post.
		if ( ! is_singular() ) {
			return false;
		}

		global $post;

		// Escaping before_widget.
		// @codingStandardsIgnoreLine
		echo $args['before_widget'];

		// Title.
		if ( empty( $instance['title'] ) ) {
			$instance['title'] = esc_html__( 'Share this article!', 'quotable-tweets' );
		}

		// Bitly access token.
		$post_link = wp_get_shortlink( $post->ID );
		if ( ! isset( $post_link ) || empty( $post_link ) || ! $post_link ) {
			$post_link = get_permalink( $post->ID );
		}

		if ( ! empty( $instance['bitly_access_token'] ) ) {
			$instance['title'] = esc_html__( 'Share this article!', 'quotable-tweets' );

			$response = wp_remote_get( 'https://api-ssl.bitly.com/v3/shorten?access_token=' . $instance['bitly_access_token'] . '&longUrl=' . rawurlencode( $post_link ) . '&format=json' );
			if ( ! is_wp_error( $response ) ) {
				$body   = wp_remote_retrieve_body( $response );
				$result = json_decode( $body );

				if ( isset( $result->data->url ) ) {
					$post_link = $result->data->url;
				}
			}
		}

		// Button Text.
		if ( empty( $instance['button_text'] ) ) {
			$instance['button_text'] = esc_html__( 'Tweet', 'quotable-tweets' );
		}

		// Escaping all outputs.
		?>
		<div class="<?php echo esc_attr( self::$prefix_dash ); ?>container">

			<div class="<?php echo esc_attr( self::$prefix_dash ); ?>title-container">
				<span aria-hidden="true" class="<?php echo esc_attr( self::$prefix_dash ); ?>icon-twitter"></span>
				<span class="<?php echo esc_attr( self::$prefix_dash ); ?>title"><?php echo esc_html( $instance['title'] ); ?></span>
			</div>

			<div class="<?php echo esc_attr( self::$prefix_dash ); ?>text-container">
				<p class="<?php echo esc_attr( self::$prefix_dash ); ?>post-title"><?php echo esc_html( $post->post_title ); ?></p>
				<p class="<?php echo esc_attr( self::$prefix_dash ); ?>quote-container">
					<span class="<?php echo esc_attr( self::$prefix_dash ); ?>quote dashicons dashicons-format-quote"></span>
				</p>
			</div>

			<a class="<?php echo esc_attr( self::$prefix_dash ); ?>button" href="https://twitter.com/intent/tweet?text=<?php echo esc_attr( $post->post_title . ' ' . $post_link ); ?>">
				<?php echo esc_html( $instance['button_text'] ); ?>
			</a>

		</div>
		<?php

		// Enqueue styles with version.
		$version = filemtime( plugin_dir_path( __FILE__ ) . 'quotable-tweets.css' );
		wp_enqueue_style( self::$prefix . 'css', plugins_url( 'quotable-tweets.css', __FILE__ ), array(), $version );
		wp_enqueue_style( 'dashicons' );

		// Escaping after_widget.
		// @codingStandardsIgnoreLine
		echo $args['after_widget'];
	}


	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from the database.
	 *
	 * @see WP_Widget::form()
	 *
	 * @return void
	 */
	public function form( $instance ) {
		$title              = ! empty( $instance['title'] )
			? $instance['title']
			: esc_html__( 'Share this article!', 'quotable-tweets' );
		$bitly_access_token = ! empty( $instance['bitly_access_token'] )
			? $instance['bitly_access_token']
			: '';
		$button_text        = ! empty( $instance['button_text'] )
			? $instance['button_text']
			: esc_html__( 'Tweet', 'quotable-tweets' );
		?>
		<p>
			<label for="
			<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
			<?php esc_html_e( 'Title:', 'quotable-tweets' ); ?> </label>
			<input class="widefat" id="
			<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>
			name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
			type="text"
			value="<?php echo esc_attr( $title ); ?>">
			<br />
			<em>
				<?php
				esc_html_e(
					'The title of the widget',
					'quotable-tweets'
				);
				?>
			</em>


		</p>
		<p>
			<label 
			for="
			<?php
			echo esc_attr(
				$this->get_field_id( 'bitly_access_token' )
			);
			?>
			"> 
			<?php
			esc_html_e(
				'Bitly Access Token:',
				'quotable-tweets'
			);
			?>
			</label>
			<input class="widefat" 
			id="<?php echo esc_attr( $this->get_field_id( 'bitly_access_token' ) ); ?>" 
			name="
			<?php echo esc_attr( $this->get_field_name( 'bitly_access_token' ) ); ?>"
			type="text" 
			value="<?php echo esc_attr( $bitly_access_token ); ?>">

			<br />
			<em> 
				<?php esc_html_e( 'Insert your Bitly', 'quotable-tweets' ); ?> 
				<a href="https://bitly.com/a/oauth_apps" target="_blank"> 
					<?php esc_html_e( 'access token', 'quotable-tweets' ); ?> 
				</a> 
				<?php
				esc_html_e(
					'Optional: Shortens post links in the tweet.',
					'quotable-tweets'
				);
				?>
			</em>
		</p>
		<p>
			<label 
			for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"> 
			<?php esc_html_e( 'Button Text:', 'quotable-tweets' ); ?> </label>
			<input class="widefat" 
			id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" 
			name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" 
			type="text" 
			value="<?php echo esc_attr( $button_text ); ?>">
			<br />
			<em> 
				<?php
				esc_html_e(
					'The text of the Tweet button.',
					'quotable-tweets'
				);
				?>
				 
			</em>
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from the database.
	 *
	 * @see WP_Widget::update()
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance                       = array();
		$instance['title']              = ! empty( $new_instance['title'] )
			? wp_strip_all_tags( $new_instance['title'] )
			: '';
		$instance['bitly_access_token'] = ! empty(
			$new_instance['bitly_access_token']
		)
			? wp_strip_all_tags( $new_instance['bitly_access_token'] )
			: '';

		$instance['button_text'] = ! empty( $new_instance['button_text'] )
			? wp_strip_all_tags( $new_instance['button_text'] )
			: '';

		return $instance;
	}
}

?>
