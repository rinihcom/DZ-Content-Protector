<?php
/**
 * Author : Budi S
 * Author URI: http://inef.web.id
 * License: GPL2
 */

class div_billing_type{
	public $wpdb;
	public $title;
	public $titles;
	public $table;
	
	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->title = 'Membership Type ';
		$this->titles = 'Membership Types ';
		$this->table = $wpdb->prefix.'div_billing_membership';
	}
		
	public function manage(){
		// Edit screen
		$messages = array();
		$wp_list_table = new div_billing_type_lists();
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
		} ?>
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
	
	public function meta_edit(){
		?>
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
		$data = $this->wpdb->get_row( "SELECT * FROM ".$this->table." WHERE mm_id='".$_GET['id']."'" );
		$data_sub = $this->wpdb->get_results( "SELECT * FROM ".$this->wpdb->prefix."div_billing_subuser_master WHERE mm_id='".$_GET['id']."'" );
		include_once( div_billing_pluginpath() . '/views/mm_form.php' );
	}

	public function addNew(){
		include_once( div_billing_pluginpath() . '/views/mm_form.php' );
	}

	private function backData(){
		$data->mm_name = $_POST['name'];
		$data->mm_type = $_POST['type'];
		$data->mm_duration = $_POST['duration'];
		$data->mm_amount = $_POST['amount'];
	}

	private function validate(){
		$this->messages = array();
		if(empty($_POST['name'])){
			$this->messages[] = 'Please fill name of membership';
		}
		if($_POST['type'] == '1'){
			if( ($_POST['onetime'] == '0' || empty($_POST['onetime'])) && (empty($_POST['allow_sub_user'])) ){
				$this->messages[] = 'Please fill amount of one time payment';
			}
		}
		if($_POST['type'] == '2'){
			if($_POST['duration'] == '0' || empty($_POST['duration'])){
				$this->messages[] = 'Please fill duration of recurring payment';
			}
			if( ($_POST['amount'] == '0' || empty($_POST['amount'])) && (empty($_POST['allow_sub_user'])) ){
				$this->messages[] = 'Please fill amount of recurring payment';
			}
		}
		if($_POST['type'] == '3'){
			if( ($_POST['onetime'] == '0' || empty($_POST['onetime'])) && (empty($_POST['allow_sub_user'])) ){
				$this->messages[] = 'Please fill amount of one time payment';
			}
			if($_POST['duration'] == '0' || empty($_POST['duration'])) {
				$this->messages[] = 'Please fill duration of recurring payment';
			}
			if( ($_POST['amount'] == '0' || empty($_POST['amount'])) && (empty($_POST['allow_sub_user'])) ){
				$this->messages[] = 'Please fill amount of recurring payment';
			}
		}
		

		return $this->messages;
	}
	
	public function post(){
		if($_POST['type'] == '0'){
			$mm_onetime = '0';
			$mm_duration_type = '0';
			$mm_duration = '0';
			$mm_amount = '0';
			$mm_allow_subuser = '0';
			$mm_status_subuser = "";
			$mm_number_subuser = '0';
		}
		else if($_POST['type'] == '1'){
			$mm_onetime = $_POST['onetime'];
			$mm_duration_type = '0';
			$mm_duration = '0';
			$mm_amount = '0';
		}
		else if($_POST['type'] == '2'){
			$mm_onetime = '0';
			$mm_duration_type = $_POST['duration_type'];
			$mm_duration = $_POST['duration'];
			$mm_amount = $_POST['amount'];
		}
		else if($_POST['type'] == '3'){
			$mm_onetime = $_POST['onetime'];
			$mm_duration_type = $_POST['duration_type'];
			$mm_duration = $_POST['duration'];
			$mm_amount = $_POST['amount'];
		}

		if($_POST['type'] != '0'){
			if($_POST['allow_sub_user']){
				$mm_allow_subuser = '1';
				/*
				$mm_status_subuser = $_POST['status_number_user'] ;
				if($mm_status_subuser == 'unlimited'){
					$mm_number_subuser = '0';
				}
				else{
					$mm_number_subuser = $_POST['number_user'];
				}
				*/
				$mm_number_subuser = "";
				foreach($_POST['from_user'] as $i => $v){
					if( !empty($v) OR !empty($_POST['to_user'][$i]) OR !empty($_POST['list_amount'][$i]) ){
						$mm_number_subuser .= $v.'::'.$_POST['to_user'][$i].'::'.$_POST['list_amount'][$i].'##';
					}
				}
			}
			else{
				$mm_allow_subuser = '0';
				$mm_status_subuser = "";
				$mm_number_subuser = '0';
			}
			
		}

		if(empty($_POST['id'])){
			// If function add
			if( $this->validate() ){
				$this->backData();
				$this->addNew();
			}
			else{
				$data = array(
					'mm_name' => $_POST['name'],
					'mm_type' => $_POST['type'],
					'mm_onetime' => $mm_onetime,
					'mm_duration_type' => $mm_duration_type,
					'mm_duration' => $mm_duration,
					'mm_amount' => $mm_amount,
					'mm_allow_subuser' => $mm_allow_subuser,
					'mm_status_subuser' => $mm_status_subuser,
					'mm_number_subuser' => $mm_number_subuser
				);
				$insert = $this->wpdb->insert($this->table,$data);
				if($insert){
					?>
						<script type="text/javascript">
							window.location = '?page=<?php echo $_GET[page]?>';
						</script>
					<?php

					if($_POST['type'] != '0'){
						if($_POST['allow_sub_user']){
							$new_id = $this->wpdb->insert_id;
							foreach($_POST['from_user'] as $i => $v){
								
								if($_POST['type'] == '1'){
									if( !empty($v) OR !empty($_POST['to_user'][$i]) OR !empty($_POST['add_amount_onetime'][$i]) ){
										$new_data = array(	'mm_id' 		=> $new_id,
															'onetime_fee'	=> $_POST['add_amount_onetime'][$i],
															'from_user' 	=> $_POST['from_user'][$i],
															'to_user' 		=> $_POST['to_user'][$i],
															'recurring_fee' => '0'
										);

										$this->wpdb->insert($this->wpdb->prefix.'div_billing_subuser_master',$new_data);
									}
								}
								else if($_POST['type'] == '2'){
									if( !empty($v) OR !empty($_POST['to_user'][$i]) OR !empty($_POST['add_amount_recurring'][$i]) ){
										$new_data = array(	'mm_id' 		=> $new_id,
															'onetime_fee'	=> '0',
															'from_user' 	=> $_POST['from_user'][$i],
															'to_user' 		=> $_POST['to_user'][$i],
															'recurring_fee' => $_POST['add_amount_recurring'][$i]
										);

										$this->wpdb->insert($this->wpdb->prefix.'div_billing_subuser_master',$new_data);
									}
								}
								else if($_POST['type'] == '3'){
									if( !empty($v) OR !empty($_POST['to_user'][$i]) OR !empty($_POST['add_amount_recurring'][$i]) ){
										$new_data = array(	'mm_id' 		=> $new_id,
															'onetime_fee'	=> $_POST['add_amount_onetime'][$i],
															'from_user' 	=> $_POST['from_user'][$i],
															'to_user' 		=> $_POST['to_user'][$i],
															'recurring_fee' => $_POST['add_amount_recurring'][$i]
										);

										$this->wpdb->insert($this->wpdb->prefix.'div_billing_subuser_master',$new_data);
									}
								}


							}

						}
					}
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
					'mm_name' => $_POST['name'],
					'mm_type' => $_POST['type'],
					'mm_onetime' => $mm_onetime,
					'mm_duration_type' => $mm_duration_type,
					'mm_duration' => $mm_duration,
					'mm_amount' => $mm_amount,
					'mm_allow_subuser' => $mm_allow_subuser,
					'mm_status_subuser' => $mm_status_subuser,
					'mm_number_subuser' => $mm_number_subuser
				);
				$where = array( 'mm_id' => $_POST['id']);
				$update = $this->wpdb->update($this->table,$data, $where);
				if($update){
					?>
						<script type="text/javascript">
							window.location = '?page=<?php echo $_GET[page]?>';
						</script>
					<?php
				}
				else{
					var_dump($update);
					$this->edit();
				}
			}
		}
	}
}

$div_billing_type = new div_billing_type;

/**
 * List table class for the Activity component admin page.
 *
 */
if(!class_exists('WP_List_Table')) :
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
endif;

class div_billing_type_lists extends WP_List_Table {
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
	protected $div_table = "div_billing_membership";

	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		// Define singular and plural labels, as well as whether we support AJAX.
		parent::__construct( array(
			'ajax'     => false,
			'plural'   => 'Membership Types',
			'singular' => 'Member Type',
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
			'mm_name'=>__('Name'),
			'mm_type'=>__('Type')
		);
	}
	
	public function get_sortable_columns() {
		$sortable_columns = array(
            'mm_name'     => array('mm_name',true),     //true means its already sorted
            'mm_type'    => array('mm_type',false)
        );
        return $sortable_columns;
	}
	
	
	function process_bulk_action() {
        
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            wp_die('Items deleted (or they would be if we had items to delete)!');
        }
        
    }
	
	function column_mm_name($item){
        
        //Build row actions
        $actions = array(
            'Edit'      => sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'],'edit',$item['mm_id']),
            'Delete Data'      => sprintf('<a href="?page=%s&action=%s&id=%s">Delete data</a>',$_REQUEST['page'],'delete',$item['mm_id'])
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['mm_name'],
            /*$2%s*/ $item['mm_id'],
            /*$3%s*/ $this->row_actions($actions)
        );
    }
	
	function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['post_id']                //The value of the checkbox should be the record's id
        );
    }
	
	function column_default($item, $column_name){
        switch($column_name){
            case 'mm_id'		:
            case 'mm_name'		:
                return $item[$column_name];
            case 'mm_type'		:
            	if($item[$column_name] == 0) return 'Free';
            	else if($item[$column_name] == 1) return 'Initial Payment';
            	else if($item[$column_name] == 2) return 'Recurring Payment';
            	else if($item[$column_name] == 3) return 'Initial then Recurring';
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
	
	function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete',
            'flag_freetime'    => 'Flag as Free Time'
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
		//$user_query = $wpdb->prepare("SELECT * FROM $this->div_table");
		$data = $wpdb->get_results("SELECT * FROM $this->div_table",ARRAY_A);
		
		/* -- Ordering parameters -- */
		//Parameters that are going to be used to order the result
		$orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : 'ASC';
		$order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : '';
		if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }

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