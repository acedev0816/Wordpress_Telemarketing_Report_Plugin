<?php
/*
Plugin Name: Telemarketing Report Plugin
Description: A report plugin to report and summarize telemarketing results
Author: Acer@King
Version: 1.0
*/
	register_activation_hook(__FILE__, 'report_install');
	register_deactivation_hook(__FILE__,'report_uninstall');
	global $jal_db_version;
	$jal_db_version = "1.0";
	
	function report_install()
	{
		global $wpdb;
		global $jal_db_version;
		
		require_once(ABSPATH.'wp-admin/includes/upgrade.php');
		//create member table
		$member = $wpdb->prefix . "member";
		$sql = "CREATE TABLE $member (
			id mediumint(0) NOT NULL AUTO_INCREMENT,
			user_id mediumint(0) NOT NULL,
			team_id mediumint(0) NOT NULL,
			PRIMARY KEY id (id)
		);";
		dbDelta($sql);
		//create team table
		$team = $wpdb->prefix . "team";
		$sql = "CREATE TABLE $team (
			id mediumint(0) NOT NULL AUTO_INCREMENT,
			name VARCHAR(120) NOT NULL,
			PRIMARY KEY id (id)
		);";
		dbDelta($sql);
			//insert initial teams
		$query = "insert into $team(id,name) values(1,'Amador Agency')";
		$wpdb->query($query);
		$query = "insert into $team(id,name) values(2,'Worth Agency')";
		$wpdb->query($query);
		$query = "insert into $team(id,name) values(3,'Fuhriman Agency')";
		$wpdb->query($query);
		$query = "insert into $team(id,name) values(4,'Pretzinger Agency')";
		$wpdb->query($query);
		$query = "insert into $team(id,name) values(5,'Espejo Agency')";
		$wpdb->query($query);
		$query = "insert into $team(id,name) values(6,'Gonzalez Agency')";
		$wpdb->query($query);
		//create result table
		$result = $wpdb->prefix . "result";
		$sql = "CREATE TABLE $result (
			id mediumint(0) NOT NULL AUTO_INCREMENT,
			member_id mediumint(0) NOT NULL,
			dial mediumint(0) NOT NULL,
			contact mediumint(0) NOT NULL,
			qt mediumint(0) NOT NULL,
			report_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY id (id)
		);";
		dbDelta($sql);
		add_option("jal_db_version", $jal_db_version);
		
	}
	add_action('admin_menu', 'report_plugin_setup_menu');
	function report_plugin_setup_menu()
	{

		add_menu_page('Report Plugin Page', 'Telemarketing Dashboard', 'manage_options', 
		'report/report-board.php','','dashicons-welcome-widgets-menus',90);
		//add front end page
	}
	/*url
	https://wordpress.org/support/topic/admin-ajax-php-400-error/
	*/
	//register ajax
	wp_enqueue_script( 'board_script', WP_PLUGIN_URL.'/report/js/board.js', array( 'jquery' ) );
	wp_localize_script('board_script', 'boardAjax',  array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));

	add_action( 'wp_ajax_handle_individual', 'handle_individual' );
	add_action( 'wp_ajax_nopriv_handle_individual', 'handle_individual' ); 

	add_action( 'wp_ajax_handle_team', 'handle_team' );
	add_action( 'wp_ajax_nopriv_handle_team', 'handle_team' ); 
	//get display name
	add_action( 'wp_ajax_handle_get_name', 'handle_get_name' );
	add_action( 'wp_ajax_nopriv_handle_get_name', 'handle_get_name' ); 
	//get team list
	add_action( 'wp_ajax_handle_get_team_list', 'handle_get_team_list' );
	add_action( 'wp_ajax_nopriv_handle_get_team_list', 'handle_get_team_list' ); 
	//post report result
	add_action( 'wp_ajax_handle_post_report', 'handle_post_report' );
	add_action( 'wp_ajax_nopriv_handle_post_report', 'handle_post_report' ); 
	//get current name in session
	function handle_get_name()
	{
		$cu = wp_get_current_user();
		$id = $cu -> ID;
		$display_name = $cu -> display_name;
		$email = $cu -> user_email;
		//default team
		global $wpdb;
		$member_table = $wpdb->prefix."member";
		$team_id = -1;
		$query = "select team_id from $member_table where user_id=$id";

		file_put_contents("log.txt", print_r($query, true));
		$rows = $wpdb->get_results($query);

		if(count($rows) != 0)
		{
			$team_id = $rows[0]->team_id;
		}
		
		//
		echo(json_encode( array("display_name" => $display_name, "team" => $team_id, "id" => $id)));
		die();
	}
	//get team list
	function handle_get_team_list()
	{
		global $wpdb;
		$team = $wpdb->prefix . "team";
		$rows = $wpdb->get_results("SELECT * from $team");
		echo json_encode($rows);
		die();
	}
	//post report 
	function handle_post_report()
	{
		global $wpdb;
		$team_table = $wpdb->prefix . "team";
		$member_table = $wpdb->prefix . "member";
		$result_table = $wpdb->prefix . "result";
		
		$member = $_POST['member'];
		$team = $_POST['team'];
		$dial = $_POST['dial'];
		$contact = $_POST['contact'];
		$qt = $_POST['qt'];    
		$cur_date = date("Y-m-d H:i:s");
		$query = "insert into $result_table(member_id,dial,contact,qt,report_time) values($member,$dial,$contact,$qt,'$cur_date')";
		
		//echo json_encode($rows);
		
		$wpdb->query($query);
		//update user's team
			//exists already?
		$query = "select id from $member_table where user_id=$member";
		$myrows = $wpdb->get_results($query);
		if(count($myrows) == 0) //if not exist : insert
		{
			$query = "insert into $member_table(user_id, team_id) values($member, $team)";
			$wpdb->query($query);
		}else{ // if exists: update
			$query = "update $member_table set team_id = $team where user_id = $member";
			$wpdb->query($query);
		}
		

		echo "yes";
		die();
	}
	//takes care of the $_POST data
	function handle_individual(){
		global $wpdb;
		$dp = $_REQUEST['dp'];
		$mp = $_REQUEST['mp'];
		$where = ''; 
		if($dp != '') //daily
		{
			$where = "report_time>='$dp' and report_time <= ADDDATE('$dp',INTERVAL 1 DAY)";
		}
		if($mp != '') //daily
		{
			$where = "report_time>='$mp/1' and report_time <= ADDDATE('$mp/1',INTERVAL 1 MONTH)";
		}
		$team = $wpdb->prefix . "team";
		$member = $wpdb->prefix . "member";
		$result = $wpdb->prefix . "result";
		$users = $wpdb->prefix . "users";
		$query = "SELECT $users.display_name AS member ,$team.name AS team,sum(dial) AS dial,
		sum(contact) AS contact ,sum(qt) AS qt FROM $result 
		LEFT JOIN $member ON($result.member_id = $member.user_id)
		LEFT JOIN $team	ON($member.team_id = $team.id)
		LEFT JOIN $users ON($member.user_id = $users.ID) 
		WHERE $where GROUP BY $users.ID ORDER BY $users.ID" ;
		//file_put_contents("log.txt", print_r($query, true));
		$myrows = $wpdb->get_results($query);
		//file_put_contents("log.txt", print_r($myrows, true));
		$customers = [];
		
		$i = 0;
		for($i = 0; $i < count($myrows); $i++)
		{
			
			$row = $myrows[$i];
			$customers[$i] = array(
				'No' => $i + 1,
				'Team' => $row->team,
				'Dials' => $row->dial,
				'Contacts' => $row->contact,
				'Qt' => $row->qt,
				'Name' => $row->member
			);
		}
		
		// file_put_contents("log.txt", print_r(json_encode($customers), true));
		echo json_encode($customers);
		die(); // this is required to return a proper result
	}
	//takes care of the $_POST data
	function handle_team(){
		global $wpdb;
		$dp = $_REQUEST['dp'];
		$mp = $_REQUEST['mp'];
		$where = ''; 
		if($dp != '') //daily
		{
			$where = "report_time>='$dp' and report_time <= ADDDATE('$dp',INTERVAL 1 DAY)";
		}
		if($mp != '') //daily
		{
			$where = "report_time>='$mp/1' and report_time <= ADDDATE('$mp/1',INTERVAL 1 MONTH)";
		}
		$team = $wpdb->prefix . "team";
		$member = $wpdb->prefix . "member";
		$result = $wpdb->prefix . "result";
		$users = $wpdb->prefix."users";
		$query = "SELECT $team.name AS team,sum(dial) AS dial,
		sum(contact) AS contact ,sum(qt) AS qt FROM $result 
		LEFT JOIN $member ON($result.member_id = $member.user_id) 
		LEFT JOIN $team ON($member.team_id = $team.id)
		LEFT JOIN $users ON($member.user_id = $users.ID)
		WHERE $where GROUP BY $team.id ORDER BY $team.id" ;
		//file_put_contents("log.txt", print_r($query, true));
		$myrows = $wpdb->get_results($query);
		//file_put_contents("log.txt", print_r($myrows, true));
		$customers = [];
		
		$i = 0;
		for($i = 0; $i < count($myrows); $i++)
		{
			
			$row = $myrows[$i];
			$customers[$i] = array(
				'No' => $i + 1,
				'Team' => $row->team,
				'Dials' => $row->dial,
				'Contacts' => $row->contact,
				'Qt' => $row->qt,
			);
		}
		
		// file_put_contents("log.txt", print_r(json_encode($customers), true));
		echo json_encode($customers);
		die(); // this is required to return a proper result
	}
	//uninstall plugin
	function report_uninstall(){
		global $wpdb;
		global $jal_dv_version;
		$result = $wpdb->prefix."result";
		$member = $wpdb->prefix."member";
		$team = $wpdb->prefix."team";
		$wpdb->query("DROP TABLE IF EXISTS $result");
		$wpdb->query("DROP TABLE IF EXISTS $team");
		$wpdb->query("DROP TABLE IF EXISTS $member");
	}

//	add_shortcode( 'qc_form', 'function_qc_form' );

?>