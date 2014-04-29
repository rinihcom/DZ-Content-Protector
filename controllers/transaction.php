<?php
/**
 * Author : Budi S
 * Author URI: http://inef.web.id
 * License: GPL2
 */

class div_billing_trans{
	public $wpdb;
	public $title;
	public $titles;
	public $table;
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->title = 'Transaction ';
		$this->titles = 'Transactions ';
		$this->table = $wpdb->prefix.'div_billing_trans';
	}
		
	public function manage(){
		// Edit screen
		$messages = array();
		$wp_list_table = new div_billing_trans_lists();
		// If the user has just made a change to an activity item, build status messages
		if ( ! empty( $_REQUEST['deleted'] ) || ! empty( $_REQUEST['spammed'] ) || ! empty( $_REQUEST['unspammed'] ) || ! empty( $_REQUEST['error'] ) || ! empty( $_REQUEST['updated'] ) ) {
			$deleted   = ! empty( $_REQUEST['deleted']   ) ? (int) $_REQUEST['deleted']   : 0;
			$errors    = ! empty( $_REQUEST['error']     ) ? $_REQUEST['error']           : '';
			$spammed   = ! empty( $_REQUEST['spammed']   ) ? (int) $_REQUEST['spammed']   : 0;
			$unspammed = ! empty( $_REQUEST['unspammed'] ) ? (int) $_REQUEST['unspammed'] : 0;
			$updated   = ! empty( $_REQUEST['updated']   ) ? (int) $_REQUEST['updated']   : 0;

			$errors = array_map( 'absint', explode( ',', $errors ) );

			// Make sure we don't get any empty values in $errors
			for ( $i = 0, $errors_count = count( $errors ); $i < $errors_count; $i++ ) {
				if ( 0 === $errors[$i] )
					unset( $errors[$i] );
			}
			if ( $updated > 0 )
				$messages[] = __( $this->title.' has been updated succesfully.', 'project-status' );
		}
		?>
		<div class="wrap">
			<?php screen_icon( 'post' ); ?>
			<h2>
				<?php echo "$this->titles"; ?> <a href="<?php printf("?page=%s&action=add",$_GET['page']) ?>" class="add-new-h2">Add New</a>
			</h2>

			<?php // If the user has just made a change to an activity item, display the status messages ?>
			<?php if ( !empty( $messages ) ) : ?>
				<div id="moderated" class="<?php echo ( ! empty( $_REQUEST['error'] ) ) ? 'error' : 'updated'; ?>"><p><?php echo implode( "<br/>\n", $messages ); ?></p></div>
			<?php endif; ?>
			
			<form id="my-dev-form" action="" method="get">
				<?php $wp_list_table->search_box( __( 'Search all '.$this->titles ), 'div-billing' ); ?>
				<input type="hidden" name="page" value="<?php echo esc_attr( $plugin_page ); ?>" />
				<?php $wp_list_table->prepare_items(); ?>
				<?php $wp_list_table->display(); ?>
			</form>
			
		</div>
		<?php
	}
	
	public function meta_edit(){ ?>
		<div class="submitbox" id="submitcomment">
			<div id="minor-publishing">
				<div class="clear"></div>
			</div><!-- #minor-publishing -->
			<div id="major-publishing-actions">
				<div id="publishing-action">
					<?php submit_button( __( 'Update' ), 'primary', 'save', false, array( 'tabindex' => '4' ) ); ?>
				</div>
				<div class="clear"></div>
			</div><!-- #major-publishing-actions -->
		</div><!-- #submitcomment -->
		<?php
	}
	
	public function edit(){
		$data = $this->wpdb->get_row( "SELECT * FROM ".$this->table." WHERE trans_id ='".$_GET['id']."'" );
		include_once( div_billing_pluginpath() . '/views/trans_form.php' );
	}

	public function addNew(){
		include_once( div_billing_pluginpath() . '/views/trans_form.php' );
	}

	private function backData(){
		$data->billing_id = $_POST['user'];
		$data->user_id = $_POST['membership'];
		$data->amount = $_POST['amount'];
		$data->status = $_POST['status'];
	}

	private function validate(){
		$this->messages = array();
		return $this->messages;
	}
	
	public function post(){
		if(empty($_POST['id'])){
			// If function add
			if( $this->validate() ){
				$this->backData();
				$this->addNew();
			}
			else{
				$data = array(
					'billing_id' => $_POST['membership'],
					'user_id' => $_POST['user'],
					'amount' => $_POST['amount'],
					'status' => $_POST['status']
				);
				$insert = $this->wpdb->insert($this->table,$data);
				if($insert){
					?>
						<script type="text/javascript">
							window.location = '?page=<?php echo $_GET[page]?>';
						</script>
					<?php
				}
				else{
					$this->edit();
				}
			}
		}
		else{
			if( $this->validate() ){
				$this->edit();
			}
			else{
				$data = array(
					'billing_id' => $_POST['membership'],
					'user_id' => $_POST['user'],
					'amount' => $_POST['amount'],
					'status' => $_POST['status']
				);
				$where = array( 'trans_id' => $_POST['id']);
				$update = $this->wpdb->update($this->table,$data, $where);
				if($update){
					?>
						<script type="text/javascript">
							window.location = '?page=<?php echo $_GET[page]?>';
						</script>
					<?php
				}
				else{
					//var_dump($update);
					$this->edit();
				}
			}
		}
	}
}

$div_billing_trans = new div_billing_trans;

/**
 * List table class for the Activity component admin page.
 *
 */
if(!class_exists('WP_List_Table')) :
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
endif;

class div_billing_trans_lists extends WP_List_Table {
	/**
	 * What type of view is being displayed? e.g. "All", "Pending", "Approved", "Spam"...
	 *
	*/
	public $view = 'all';

	/**
	 * Store User ID
	 *
	 */
	protected $user_id;
	protected $div_table = "div_billing_trans";

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		// Define singular and plural labels, as well as whether we support AJAX.
		parent::__construct( array(
			'ajax'     => false,
			'plural'   => 'Transactions',
			'singular' => 'Transaction',
		) );

		global $wpdb;
		$this->div_table = $wpdb->prefix.$this->div_table;
	}
	
	/**
	 * Add extra markup in the toolbars before or after the list
	 * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
	 */
	function extra_tablenav( $which ) {
		if ( $which == "top" ){
		
		}
		if ( $which == "bottom" ){
		
		}
	}
	
	/**
	 * Define the columns that are going to be used in the table
	 * @return array $columns, the array of columns to use with the table
	 */
	function get_columns() {
		return $columns= array(
			'cb'  => '<input type="checkbox" />',
			'trans_id'=>__('Transaction ID'),
			'user_id'=>__('User'),
			'billing_id'=>__('Group Membership'),
			'amount'=>__('Amount'),
			'status'=>__('Status'),
		);
	}
	
	public function get_sortable_columns() {
		$sortable_columns = array(
            'trans_id'     => array('trans_id',true),     //true means its already sorted
            'user_id'    => array('user_id',false),
            'billing_id'    => array('billing_id',false),
            'amount'    => array('amount',false),
            'status'  => array('status',false)
        );
        return $sortable_columns;
	}
	
	
	function process_bulk_action() {
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
	
	function column_trans_id($item){
        
        //Build row actions
        $actions = array(
            'Edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['trans_id']),
            'Delete Data'      => sprintf('<a href="?page=%s&action=%s&id=%s">Delete data</a>',$_REQUEST['page'],'delete',$item['trans_id'])
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['trans_id'],
            /*$2%s*/ $item['user_id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
	
	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['trans_id']                //The value of the checkbox should be the record's id
        );
    }
	
	function column_default($item, $column_name){
		global $wpdb;
        switch($column_name){
            case 'trans_id'		:
            case 'status'			:
            case 'amount'			:
                return $item[$column_name];
                break;
            case 'user_id'	:
            	return get_userdata($item[$column_name])->display_name;
            	break;
            case 'billing_id'		:
            	$datas = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."div_billing_membership WHERE mm_id='$item[$column_name]'");
            	return $datas->mm_name;
            	break;
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
	
	function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    }
	
	
	/**
	 * Prepare the table with different parameters, pagination, columns and table elements
	 */
	function prepare_items() {
		global $wpdb, $_wp_column_headers;
		$screen = get_current_screen();

		/* -- Preparing your query -- */
		
		/** Get data dev from usermeta */
		$data = array();
		//$user_query = $wpdb->prepare();
		$data = $wpdb->get_results("SELECT * FROM $this->div_table",ARRAY_A);
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
		if(!empty($orderby) & !empty($order)){ $query .=' ORDER BY '.$orderby.' '.$order; }

		$per_page = 8;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
		
        $this->_column_headers = array($columns, $hidden, $sortable);
        
        
        /**
         * Optional. You can handle your bulk actions however you see fit. In this
         * case, we'll handle them within our package just to keep things clean.
         */
        $this->process_bulk_action();
		
        //$data = $wpdb->get_results($query,ARRAY_A);
                
        $current_page = $this->get_pagenum();
        $total_items = count($data);
		
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
		
        $this->items = $data;
        /**
         * REQUIRED. We also have to register our pagination options & calculations.
         */
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }
}
?>