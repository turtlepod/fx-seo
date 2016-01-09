<?php
/* do not access this file directly */
if ( ! defined( 'WPINC' ) ) { die; }

/**
 * SEO Settings
 * Create settings page in "Settings > SEO"
 * @since 0.1.0
 */

/**
 * Create Settings Page
 * @since 0.1.0
 */
class fx_SEO_Settings{

	/**
	 * Settings Page Slug
	 * @since 0.1.0
	 */
	public $settings_slug = 'fx-seo';

	/**
	 * Settings Page Hook Name
	 * @since 0.1.0
	 */
	public $settings_id = 'settings_page_fx-seo';

	/**
	 * Options Group
	 * @since 0.1.0
	 */
	public $options_group = 'fx-seo';

	/**
	 * Option Name
	 * @since 0.1.0
	 */
	public $option_name = 'fx-seo';

	/**
	 * Start
	 * @since 0.1.0
	 */
	public function __construct(){

		/* Create Settings Page */
		add_action( 'admin_menu', array( $this, 'create_settings_page' ) );

		/* Register Settings and Fields */
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Create Settings Page
	 * @since 0.1.0
	 */
	public function create_settings_page(){

		/* Create Settings Sub-Menu */
		$settings_page = add_submenu_page( 
			'options-general.php', // parent slug
			_x( 'SEO Settings', 'settings page', 'fx-seo' ), // page title
			_x( 'SEO', 'settings page', 'fx-seo' ), // menu title
			'manage_options',  // capability
			$this->settings_slug, // page slug
			array( $this, 'settings_page' ) // callback functions
		);

		/* Do stuff in settings page, such as adding scripts, etc. */
		if ( $settings_page ) {

			/* Load the JavaScript needed for the settings screen. */
			add_action( 'admin_enqueue_scripts', array( $this, 'settings_scripts' ) );

			/* Blog public options. */
			$blog_public = get_option( 'blog_public' );
			if( !$blog_public ){
				add_action( 'admin_notices', array( $this, 'blog_public_notice' ) );
			}
		}

	}

	/**
	 * Settings Scripts
	 * @since 0.1.0
	 */
	public function settings_scripts( $hook_suffix ){
		if ( $this->settings_id == $hook_suffix ){
			wp_enqueue_style( 'fx-seo-admin', FX_SEO_URI . 'css/settings.css', array(), FX_SEO_VERSION );
			
		}
	}

	/**
	 * Settings Page Output
	 * @since 0.1.0
	 */
	public function settings_page(){
	?>
		<div class="wrap">

			<h1><?php _ex( 'SEO Settings', 'settings page', 'fx-login-notification' ); ?></h1>

			<form method="post" action="options.php">
				<?php settings_fields( $this->options_group ); ?>
				<?php do_settings_sections( $this->settings_slug ); ?>
				<?php submit_button(); ?>
			</form>

		</div><!-- wrap -->
	<?php
	}

	/**
	 * Blog Public Admin Notice
	 * @since 0.1.0
	 */
	public function blog_public_notice(){
		global $hook_suffix;
		if ( $this->settings_id == $hook_suffix || 'options-reading.php' == $hook_suffix ){
		?>
		<div class="error">
			<?php echo wpautop( sprintf( _x( "<strong>SEO Notice:</strong> You're blocking access to robots. Change your <strong>Search Engine Visibility Option</strong> in <a href='%s'>Reading Settings</a> to fix this.", 'settings page', 'fx-seo' ), esc_url( admin_url( 'options-reading.php' ) ) ) );?>
		</div>
		<?php
		}
	}

	/**
	 * Sanitize Options
	 * @since 0.1.0
	 */
	public function sanitize( $data ){

		/* New Data */
		$new_data = array();

		/* Front Page: Title Tag */
		if( isset( $data['front_page_title'] ) ){
			$new_data['front_page_title'] = esc_attr( strip_tags( $data['front_page_title'] ) );
		}
		/* Front Page: Meta Description */
		if( isset( $data['front_page_meta_desc'] ) ){
			$new_data['front_page_meta_desc'] = esc_attr( strip_tags( $data['front_page_meta_desc'] ) );
		}
		/* Template: Title Tag */
		if( isset( $data['template_title'] ) ){
			$new_data['template_title'] = esc_attr( strip_tags( $data['template_title'] ) );
		}
		/* Template: Meta Description */
		if( isset( $data['template_meta_desc'] ) ){
			$new_data['template_meta_desc'] = esc_attr( strip_tags( $data['template_meta_desc'] ) );
		}

		return $new_data;
	}

	/**
	 * Register Settings
	 * @since 0.1.0
	 */
	public function register_settings(){

		/* Register settings */
		register_setting(
			$this->options_group, // options group
			$this->option_name, // option name/database
			array( $this, 'sanitize' ) // sanitize callback function
		);

		/* === #2 Section: Front Page === */
		add_settings_section(
			'fx_seo_front_page_section', // section ID
			_x( 'Front Page (Home Page)', 'settings page', 'fx-seo' ), // section title
			'__return_false', // section callback function
			$this->settings_slug // settings page slug
		);

		/* Field: Front Page Title */
		add_settings_field(
			'fx_seo_front_page_title', // field ID
			_x( 'Front page title', 'settings page', 'fx-seo' ), // field title 
			array( $this, 'settings_field_front_page_title' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_seo_front_page_section' // section ID
		);

		/* Field: Front Page Meta Description */
		add_settings_field(
			'fx_seo_front_page_meta_description', // field ID
			_x( 'Front page meta description', 'settings page', 'fx-seo' ), // field title 
			array( $this, 'settings_field_front_page_meta_description' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_seo_front_page_section' // section ID
		);

		
		/* === #3 Section: Template Pattern === */
		add_settings_section(
			'fx_seo_template', // section ID
			_x( 'Title & Meta Template', 'settings page', 'fx-seo' ), // section title
			'__return_false', // section callback function
			$this->settings_slug // settings page slug
		);

		/* Field: Title Tag Pattern/Template */
		add_settings_field(
			'fx_seo_template_title', // field ID
			_x( 'Title template', 'settings page', 'fx-seo' ), // field title 
			array( $this, 'settings_field_template_title' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_seo_template' // section ID
		);

		/* Field: Meta Description Pattern/Template */
		add_settings_field(
			'fx_seo_template_meta_description', // field ID
			_x( 'Meta description template', 'settings page', 'fx-seo' ), // field title 
			array( $this, 'settings_field_template_meta_description' ), // field callback function
			$this->settings_slug, // settings page slug
			'fx_seo_template' // section ID
		);

	}

	/**
	 * Settings Filed Callback: Front Page Title Tag
	 * @since 0.1.0
	 */
	public function settings_field_front_page_title(){
	?>
		<input type="text" value="<?php echo strip_tags( fx_seo_get_option( 'front_page_title' ) ); ?>" placeholder="<?php echo strip_tags( get_bloginfo( 'name' ) ); ?>" name="fx-seo[front_page_title]" class="regular-text">
		<p class="description"><?php _ex( 'Your current site title is:', 'settings page', 'fx-seo' );?> "<?php echo get_bloginfo( 'name' ); ?>".</p>
	<?php
	}

	/**
	 * Settings Filed Callback: Front Page Meta Description
	 * @since 0.1.0
	 */
	public function settings_field_front_page_meta_description(){
	?>
		<textarea name="fx-seo[front_page_meta_desc]" class="regular-text" placeholder="<?php echo strip_tags( get_bloginfo( 'description' ) ); ?>"><?php echo strip_tags( fx_seo_get_option( 'front_page_meta_desc' ) ); ?></textarea>
		<p class="description"><?php _ex( 'Your current tagline is:', 'settings page', 'fx-seo' );?> "<?php echo get_bloginfo( 'description' ); ?>".</p>
	<?php
	}
	/**
	 * Settings Filed Callback: Title Tag Template
	 * @since 0.1.0
	 */
	public function settings_field_template_title(){
	?>
		<input type="text" value="<?php echo strip_tags( fx_seo_get_option( 'template_title' ) ); ?>" name="fx-seo[template_title]" class="regular-text" placeholder="<?php echo strip_tags( '%current_title% &ndash; %site_title%' ); ?>">
		<?php
			$explain = fx_seo_settings_template_title_explain();
			foreach( $explain as $tag => $desc ){
			?>
				<p class="description">%<?php echo $tag ?>% &mdash; <?php echo $desc; ?></p>
			<?php
			}
		?>
	<?php
	}

	/**
	 * Settings Filed Callback: Meta Description Template
	 * @since 0.1.0
	 */
	public function settings_field_template_meta_description(){
	?>
		<input type="text" value="<?php echo strip_tags( fx_seo_get_option( 'template_meta_desc' ) ); ?>" name="fx-seo[template_meta_desc]" class="regular-text" placeholder="%current_description%">
		<?php
			$explain = fx_seo_settings_template_meta_desc();
			foreach( $explain as $tag => $desc ){
			?>
				<p class="description">%<?php echo $tag ?>% &mdash; <?php echo $desc; ?></p>
			<?php
			}
		?>
	<?php
	}
}
