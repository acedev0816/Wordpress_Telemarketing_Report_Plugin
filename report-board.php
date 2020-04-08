    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" >
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <!-- font -->
    <link rel=" stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css ">
    <!-- jqx -->
    <link rel="stylesheet" href="<?php echo plugins_url('report/css/jqx/jqx.base.css'); ?>" type="text/css" />
    <link rel="stylesheet" href="<?php echo plugins_url('report/css/jqx/jqx.classic.css'); ?>" type="text/css" />
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxcore.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxbuttons.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxscrollbar.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxmenu.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxgrid.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxgrid.selection.js'); ?>"></script>	
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxgrid.filter.js'); ?>"></script>	
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxgrid.sort.js'); ?>"></script>		
    <script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxdata.js'); ?>"></script>	
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxlistbox.js'); ?>"></script>	
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxgrid.pager.js'); ?>"></script>		
	<script type="text/javascript" src="<?php echo plugins_url('report/js/jqx/jqxdropdownlist.js'); ?>"></script>	
    <!-- custom -->
    <link href="<?php echo plugins_url('report/css/board.css');?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo plugins_url('report/js/board.js'); ?>"></script>

    <!-- datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <div class="container contact-form">
        <div class = "text-right">
            <img src="<?php echo plugins_url('report/img/logo.png'); ?>" alt="rocket_contact " width=200px height=80px />
        </div>
        
        <h2 class="mytitle" >Score Board</h2>
        <span class="badge badge-secondary">
            <h4>Options</h4>
        </span>
        <div class="row d-flex p-1" style="border-bottom:2px solid black;border-top:2px solid black">
            <label class="col-form-label form-control-label ">Period:</label>
            <select  name="period"  id="period" style="margin-left:10px;">
                <option value="1 "> Daily</option>
                <option value="2 "> Monthly</option>
            </select>
            <input type="text"  id="datepicker" style="margin-left:10px;position:relative; z-index:10000" > </input>
            <input type="text"  id="monthpicker" style="margin-left:10px;position:relative; z-index:10001; display:none;" > </input>
        </div>
        <span class="badge badge-secondary">
            <h3>Team results</h3>
        </span>
        <div class="row" >
             <div id="team_table">
            </div>
        </div>      
        <span class="badge badge-secondary">
            <h3>Individual results</h3>
        </span>
        <div class="row" >
             <div id="individual_table">
            </div>
        </div>
    </div>