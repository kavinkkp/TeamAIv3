<?php
namespace Wpmet\Service;

defined( 'ABSPATH' ) || exit;

/*
USAGE:

\Wpmet\Service\Notice::instance('elementskit-lite', 'test3')
->unique(true)
->dismissible('global', (60))
->add_title('moo 32')
->add_message('my world message 2')
->add_logo('https://ps.w.org/elementor/assets/icon-256x256.png', "max-height: 100px")
->add_button([
    'url' => 'www.google.com',
    'text' => 'foo button',
    'class' => 'button-primary'
])
->add_button([
    'url' => 'www.google.com',
    'text' => 'foo button 2',
    'icon' => 'dashicons-before dashicons-admin-plugins'
])
->add_button([
    'url' => 'www.google.com',
    'text' => 'foo button 2',
    'icon' => 'dashicons-before dashicons-admin-page'
])
->add_html('<p>bar</p>')
->init();

*/
if(!class_exists('\Wpmet\Service\Notice')):
class Notice{

        
    /**
     * Unique ID to identify each notice
     *
     * @var string
     */
    protected $notice_id;

    /**
     * Plugin text-domain
     *
     * @var string
     */
    protected $text_domain;

    
    /**
     * Unique ID
     *
     * @var string
     */
    protected $unique_id;

    
    /**
     * Notice div container's class
     *
     * @var string
     */
    protected $class;

    
    /**
     * Single button's data
     *
     * @var array
     */
    protected $button;

    
    /**
     * Size class
     *
     * @var array
     */
    protected $size;

    
    /**
     * List of all buttons with it's config data
     *
     * @var array
     */
    protected $buttons;

    /**
     * Notice title
     *
     * @var string
     */
    protected $title;

    
    /**
     * Notice message
     *
     * @var string
     */
    protected $message;

    /**
     * Left logo
     *
     * @var string
     */
    protected $logo;

    /**
     * Left logo style
     *
     * @var string
     */
    protected $logo_style;


    /**
     * Left logo style
     *
     * @var string
     */
    protected $dismissible;

    protected $expired_time;


    
    /**
     * html markup for notice
     *
     * @var string
     */
    protected $html;

    
    /**
     * Constractor
     *
     * @return void
     */
    public function __construct(){
        add_action(	'admin_footer', [ $this, 'enqueue_scripts' ], 9999);
    }


    // config
    
    /**
     * Configures all setter variables
     *
     * @param  string $prefix
     * @return void
     */
    public function config(string $text_domain = '', string $unique_id = ''){
        $this->text_domain = $text_domain;

        $this->unique_id = $unique_id;

        $this->notice_id = $text_domain . '-' . $unique_id;

        $this->dismissible = false; // false, user, global

        $this->expired_time = 1;

        $this->html = '';

        $this->title = '';
        
        $this->message = '';
        
        $this->class = '';

        $this->logo = '';

        $this->logo_style = '';

        $this->size = [ ];

        $this->button = [
            'default_class' => 'button',
            'class' => 'button-secondary', // button-primary button-secondary button-small button-large button-link
            'text' => 'Button',
            'url' => '#',
            'icon' => ''
        ];

        $this->buttons = [];

        return $this;
    }

    // setters begin
    
    /**
     * Adds classes to the container
     *
     * @param  string $classname
     * @return void
     */
    public function add_class(string $classname = ''){
        $this->class .= $classname;

        return $this;
    }
    
    public function add_type(string $type = ''){
        $this->class .= 'notice-' . $type;

        return $this;
    }

    public function add_button(array $button = []){
        $button = array_merge($this->button, $button);
        $this->buttons[] = $button;

        return $this;
    }

    public function add_title(string $title = ''){
        $this->title .= $title;

        return $this;
    }

    public function add_message(string $message = ''){
        $this->message .= $message;

        return $this;
    }

    public function add_logo(string $logo = '', string $logo_style = ""){
        $this->logo = $logo;

        $this->logo_style = $logo_style;

        return $this;
    }

    public function add_html(string $html = ''){
        $this->html .= $html;

        return $this;
    }

    // setters ends


    // group getter
    public function get_data(){
        return [
            'message' => $this->message,
            'title' => $this->title,
            'buttons' => $this->buttons,
            'class' => $this->class,
            'html' => $this->html,
        ];
    }




    public function init(){
        // if(!is_admin()){
        //     return;
        // }
        
        add_action( 'admin_notices', [$this, 'get_notice'] );
        add_action( 'wp_ajax_wp-advanced-notices', [ $this, 'dismiss_ajax_call' ] );
    }

    public function get_notice(){

        // dismissible conditions
		if ( 'user' === $this->dismissible) {
			$expired = get_user_meta( get_current_user_id(), $this->notice_id, true );
		} elseif ( 'global' === $this->dismissible ) {
			$expired = get_transient( $this->notice_id );
        }else{
            $expired = '';
        }
        
        // echo $expired; exit;

		// notice visible after transient expire.
		if ( false === $this->notice_id ) {
            $this->generate_html();
        }else{
            // is transient expired?
            if ( false === $expired || empty( $expired ) ) {
                $this->generate_html();
            }
        }

    }

    public function dismissible($scope, $time){
        $this->dismissible = $scope;
        $this->expired_time = $time;
        
        return $this;
    }

    public function dismiss_ajax_call() {
		$notice_id   = ( isset( $_POST['notice_id'] ) ) ? $_POST['notice_id'] : '';
		$dismissible = ( isset( $_POST['dismissible'] ) ) ? $_POST['dismissible'] : '';
		$expired_time = ( isset( $_POST['expired_time'] ) ) ? $_POST['expired_time'] : '';
        // print_r([$notice_id, $dismissible, $expired_time]);

		if ( ! empty( $notice_id ) ) {
			if ( 'user' === $dismissible ) {
				update_user_meta( get_current_user_id(), $notice_id, true );
			} else {
				set_transient( $notice_id, true, $expired_time );
			}

			wp_send_json_success();
		}

		wp_send_json_error();
	}
    
    public function  generate_html() {

		?>
        <div 
            id="<?php echo esc_attr($this->notice_id); ?>" 
            class="notice wp-advanced-notice notice-<?php echo esc_attr($this->notice_id . ' ' .$this->class); ?> <?php echo (false === $this->dismissible ? '' : 'is-dismissible') ;?>"

            expired_time="<?php echo ($this->expired_time); ?>"
            dismissible="<?php echo ($this->dismissible); ?>"
        >
            <?php if(!empty($this->logo)):?>
                <div class="notice-left-container alignleft">
                    <p><img style="margin-right:15px; <?php echo esc_attr($this->logo_style); ?>" src="<?php echo esc_url($this->logo);?>" /></p>
                </div>
            <?php endif; ?>

            <div class="notice-right-container">

                <?php if(empty($this->html)): ?>
                    <div class="extension-message">
                        <?php echo (empty($this->title) ? '' : sprintf('<h3>%s</h3>', $this->title)); ?>
                        <?php echo ( $this->message );?>
                    </div>

                    <?php if(!empty($this->buttons)): ?>
                        <div class="submit">
                            <?php foreach($this->buttons as $button): ?>
                                <a href="<?php echo esc_url($button['url']); ?>" class="<?php echo esc_attr($button['class']); ?>">
                                    <?php if(!empty($button['icon'])) :?>
                                        <i class="notice-icon <?php echo esc_attr($button['icon']); ?>"></i>
                                    <?php endif; ?>
                                    <?php echo esc_html($button['text']);?>
                                </a>
                                &nbsp;
                            <?php endforeach; ?>
                        </div>
                    <?php endif;?>

                <?php else:?>
                    <?php echo $this->html; ?>
                <?php endif;?>

                <?php if(false !== $this->dismissible): ?>
                    <button type="button" class="notice-dismiss">
                        <span class="screen-reader-text">x</span>
                    </button>
                <?php endif;?>

            </div>
            <div style="clear:both"></div>

        </div>
		<?php
	}

	public function enqueue_scripts() {
		echo "
			<script>
                jQuery(document).ready(function ($) {
                    $( '.wp-advanced-notice.is-dismissible' ).on( 'click', '.notice-dismiss', function() {

                        _this 		= $( this ).parents('.wp-advanced-notice').eq(0);
                        var notice_id 	= _this.attr( 'id' ) || '';
                        var expired_time 	= _this.attr( 'expired_time' ) || '';
                        var dismissible 	= _this.attr( 'dismissible' ) || '';
                        var x = $( this ).attr('class');
                console.log({
                    _this, x, notice_id, expired_time, dismissible
                });
                // return;
                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action 	: 'wp-advanced-notices',
                                notice_id 		: notice_id,
                                dismissible 	: dismissible,
                                expired_time 	: expired_time,
                            },
                        });
                    });
                });
            </script>
            <style>
                .notice .notice-icon{
                    display:inline-block;
                }
                .notice .notice-icon:before{
                    vertical-align: middle!important;
                    margin-top: -1px;
                }
            </style>
		";
    }
    

    private static $instance;
	
	/**
	 * init
	 *
	 * @return object
	 */
	public static function instance($text_domain = null, $unique_id = null) {
        if($text_domain == null){
            return false;
        }

		if(!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance->config($text_domain, (is_null($unique_id) ? uniqid() : $unique_id));
	}
}

endif;