<div class="navigation"><a href="<?php echo $base;?>index.php">Home</a> &rsaquo;&rsaquo; My Account</div>
<div id="consultation_outer">
<div id="refill_outer">
<div id="createaccount_head">My Account</div>
<div id="createaccount_line"></div>
<!---->

<div id="TabbedPanels1" class="TabbedPanels">
  <ul class="TabbedPanelsTabGroup">
    <li class="TabbedPanelsTab" tabindex="0">Account Details</li>
    <li class="TabbedPanelsTab" tabindex="0">Order Status and History</li>
  </ul>
  <div class="TabbedPanelsContentGroup">
  <!--content1-->
    <div class="TabbedPanelsContent">
<!--one row-->    
<div class="onerowaccount">
	<div class="accountdetails accountdeatilseach">
	<h2> Account Details</h2>
	<?php foreach($user_data as $userdata1)
	{?>
	<p>Email Address:<br />
    <?php 
    echo $userdata1['email'];
    ?><br />
    Password:<br/>
    <?php echo "*****";?>
</p>
    <p>
    Security Question:<br />
    <?php echo $userdata1['securityquest'];?><br />
    Security Answer:<br />
    <?php 
		echo "***********";
    ?>
    </p>
    <?php 
	}
    ?>
    <div class="editbutton1 floatright"><a href="<?php echo $base;?>index.php/myaccount_controller/editacountdetails/<?php echo $id;?>" style="text-decoration:none;color:#FFFFFF;">Edit</a> </div>
    </div>
            <div class="shippinginformation accountdeatilseach">
            	<h2> Shipping Information</h2>
                <p>Address on file: 1<br />
    Default:<br />
    tiju<br />
    tiju kt <br />
    1001, mainstreet, first floor, arcade mall<br />
    bridgeport NY
    <b> Phone:</b>103-564-635</p>
                
                <div class="editbutton1 floatright"><a href="<?php echo $base;?>index.php/myaccount_controller/edit_shipping_information" style="text-decoration:none;color:#FFFFFF;">Edit</a> </div>
                </div>
            
            
            
            <div class="prescription accountdeatilseach">
            	<h2>Prescription Center</h2>
             <p><i>Managing Prescriptions is easy on : </i> </p>
            <div class="refill_listing">
    			<ul>
                <li><b><a href="<?php echo $base;?>index.php/prescription/newprescription" style="text-decoration:none;color:black;">Order refills with one click</a></b> </li>
                <li><b>Set Refill Reminders</b> and never miss a dose</li>
                <li><b><a href="<?php echo $base;?>index.php/onlineshop" style="text-decoration:none;color:black;">And more!</a></b></li>
                </ul></div> 
                
              <!--<div class="refill_listing textpadding"><i><u>Sign up for prescription managment </u>  to take advantage of all the online features or <u>Learn More</u>  </i> </div>
    		
            <div class="editbutton1 floatright">Edit </div>-->
            </div>
   
    </div>    

<!--second row-->

<div class="secondrow">

	<div class="personalinfo accountdeatilseach">
	<?php foreach($user_data as $userdata1)
            {?>
    <h2> Personal Information</h2>
                <p>&nbsp;</p>
                <p><?php echo $userdata1['firstname'].$userdata1['lastname'];?><br />
                  Phone: <?php echo $userdata1['phone'];?><br />
                  Email Adress: <a href="mailto:"><?php echo $userdata1['email'];?></a><br />
  <b>Preferred Contact Method:</b> Email</p>
                <div class="editbutton1 floatright"><a href="<?php echo $base;?>index.php/myaccount_controller/edit_personal_info/<?php echo $id;?>" style="text-decoration:none;color:#FFFFFF;">Edit</a> </div>
    </div>
    <?php
}
    ?>
<!---->
<div class="personalinfo accountdeatilseach">
    <h2>Favorite MSP/ Pharmacy store</h2>
                <p>&nbsp;</p>
                <p>Favorite stores on file: 0<br />
                  Add your favorite MSP/ pharmacy store<br />
                  now by clicking Edit/Add/View <br />
  </p>
                <div class="editbutton1 floatright"><a href="<?php echo $base;?>index.php/myaccount_controller/edit_pharmacy_store/<?php echo $id;?>" style="text-decoration:none;color:#FFFFFF;">Edit</a></div>
    </div>
    <!---->
    
<div class="billingginfo_bound">
	<div class="billinginfo accountdeatilseach">
    
    <h2>Billing Information</h2>

                <p>Credit Card(s) on File: 0   <span><a href="<?php echo $base;?>index.php/myaccount_controller/edit_billing_information/<?php echo $id;?>">Edit/ Add/ View</a> </span>  <br /><br />
                FSA Debit Cards on File: 0   <span><a href="<?php echo $base;?>index.php/myaccount_controller/edit_billing_information/<?php echo $id;?>">Edit/ Add/ View</a></span> </p>
    </div>
    
    <div class="emailcomm accountdeatilseach">
    
    <h2>Email Communications</h2>

                <p>Be among the first to know about the sales new product information and special offer from MSP/ pharmacy<br /><br />
                <b>Email Status:</b><?php echo $userdata1['subscribed'];?> </p>
                <div class="emaileditbutton floatright"><a href="<?php echo $base;?>index.php/myaccount_controller/edit_email_communication/<?php  echo $id;?>" style="text-decoration:none;color:#FFFFFF;">Edit</a> </div>
    </div>
</div>
</div> 
    </div>
    
    
    <!--@@@@@@@@@@@@@@@@@@@@@@@@@@@@Content-2@@@@@@@@@@@@@@@@@@@@@@-->
    
    
    <div class="TabbedPanelsContent">
    <div class="orderstatusonerow"> <p>Your online order history does not reflect returns, adjustments or exchanges. If you have any questions or require further assistance, please
	contact us at (888)607-4287.</p>
    <h2>Order Status & History</h2>
    <table width="930" border="0">
  <tr >
    <td width="150"><h3>Refine By:</h3></td>
    <td width="150">&nbsp;</td>
    <td width="150">&nbsp;</td>
    <td width="150">&nbsp;</td>
    <td width="150">&nbsp;</td>
    <td width="150">&nbsp;</td>
  </tr>
  <tr>
    <td height="41" colspan="2"><b>Time Period</b>
      <form id="form1" name="form1" method="post" action="">
        <label>
        <select name="select" size="1" class="orderstatusonerow_list" id="select">
          <option>Last 3 Months</option>
          <option>Last 6 Months</option>
          <option>Last year</option>
          <option>Last 18 Months</option>
                                </select>
        </label>
      </form>      </td>
    <td colspan="2"><b>Order Types</b>
    <form id="form1" name="form1" method="post" action="">
        <label>
        <select name="select" size="1" class="orderstatusonerow_list" id="select">
          <option>View all order types</option>
          <option>Nonprescription</option>
          <option>Prescription refills only</option>
          <option>Prescription transfers only</option>
          <option>Prescription renew only</option>
          <option>New prescription orders only</option>
                      </select>
        </label>
      </form>    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="36" >&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td height="36" class="orderdetails"><a href="#" > Order Number</a></td>
    <td class="orderdetails"><a href="#" > Date Ordered</a></td>
    <td class="orderdetails">Order Type</td>
    <td class="orderdetails">Status</td>
    <td class="orderdetails">Delivery Type</td>
    <td class="orderdetails noborder">Total</td>
  </tr>
</table>
    </div>
    </div>
  </div>
</div>
</div>
</div>
<p>&nbsp;</p>
</div>
<div class="clear"></div>
