<?php
class Home extends CI_Controller {
   
   var $base;
   var $css;
   
	  function __construct()
       {
        parent::__construct();	
		$this->base = $this->config->item('base_url');
		$this->css = "style.css";
		$this->load->helper('parameter');
		$this->load->database();
		$this->load->helper('form');
		$this->load->library('session');
		$this->load->library('pagination');
		$this->load->model('home_model');
		$this->load->helper(array('form', 'url'));
		$this->load->helper('menu');
		$this->load->library('image_lib');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->helper('download');
		$this->load->helper('file');
		
	}
	
	function index()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="index";
	   $this->session->set_userdata('url', current_url());
	   $data['pr_image']=$this->home_model->product_image();
	   $data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
		//echo $this->session->userdata('memberid');
		//echo $this->session->userdata('cartid');
	   $data['msg']='0';
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
	   $this->form_validation->set_rules('email','Email','required|valid_email');
		if($this->form_validation->run()==TRUE)
		{
			$filename=get_filenames($this->base."newsletter/");
			$data=file_get_contents($this->base."newsletter/".$filename[0]);
			$name="newsletter.pdf";
			force_download($name,$data);
			$data['val']="1";
		}
		else
		{
			$data['val']="0";
		}
		$this->load->view('header',$data);
		$this->load->view('home_new',$data);
		$this->load->view('footer',$data);
		
	}
	function signin()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['message']="";
	   $data['menu']="";
	   $data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
	   $this->form_validation->set_rules('email', 'E-mail Id', 'callback_check|email');
	   $this->form_validation->set_rules('password', 'Password', 'callback_check');
	   $this->load->view('header',$data);
	   
	   if ($this->form_validation->run() == TRUE)
	   {
	   $insert['email']=$this->input->post('email');
	   $insert['password']=base64_encode($this->input->post('password'));
	   
	   $login=$this->home_model->signin($insert);
	   $no=$login->num_rows;
	   
	   if($no>0)
	   {
		   
		   foreach($login->result() as $log)
		   {
		   }
		 
		  $this->session->set_userdata('memberid', $log->memberid);
		  $this->session->set_userdata('name', $log->firstname);
		  $this->session->set_userdata('email', $log->email);
		  //$this->session->set_userdata('url', current_url()); 
		  
		  redirect($this->session->userdata('url'), 'refresh');
		  
	     // $this->load->view('home',$data);
       }
	   else
		{
			$data['message']="SORRY..You are not a member of MSP.Please create an account.";
			$data['menu']="signin";
			$this->load->view('signin',$data);
			
		}
       }
		else
		{
		    $data['message']="SORRY..You are not a member of MSP.Please create an account.";

			$this->load->view('signin',$data);
			
		}
		
		
		$this->load->view('footer',$data);
	}
	function createaccount()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="";
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
 	   $data['pharmacy']=$this->home_model->pharmacy();
		$this->load->view('header',$data);
		$this->load->view('createaccount',$data);
		$this->load->view('footer',$data);
	}
	function forgotpassword()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['message'] ="";
	   $data['menu']="";
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
	   $this->load->library('email');
		$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
		$this->form_validation->set_rules('email', 'E-mail Id', 'valid_email|required');
		$this->load->view('header',$data);
		if ($this->form_validation->run() == TRUE)
		{
			$email=$this->input->post('email');
		    $va=$this->home_model->view("member",$email);
			$no=$va->num_rows;
			foreach($va->result() as $vaa)
			{
			}
			$pass=base64_decode($vaa->password);
			
			if($no>0)
			{
				
				$this->email->from('www.mainstreetpharmacys.com', 'MSP');
				$this->email->to($email);
				$this->email->cc('tintu.primemove@gmail.com');
				
                 $message="Your password is <b>".$pass."</b>";
				$this->email->subject('Password recovery from Main Street Pharmacy');
				$this->email->message($message);

				$this->email->send();

				$this->load->view('signin',$data);
				
			}
		}
		
		else
		{
		$this->load->view('forgotpassword',$data);
	    }
		$this->load->view('footer',$data);
		
	}
	function newpassword()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="";
	   $data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
		$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
		$this->form_validation->set_rules('password', 'Password', 'required|matches[repassword]|min_length[7]|max_length[20]');
		$this->form_validation->set_rules('repassword', 'Re Enter Password', 'required');
		
		$this->load->view('header',$data);
		if ($this->form_validation->run() == TRUE)
		{
			$password=$this->input->post('password');
			$repassword=$this->input->post('repassword');
			$va=$this->home_model->updatepassword($password);
			$this->load->view('newpasswordsecurity',$data);
			
		}
		else
		{
			$this->load->view('newpassword',$data);
	    }
		
		
		$this->load->view('footer',$data);
		
	}
	function accountconfirmation()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css; 
	   $data['menu']="";
	   $data['message']="";
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
	   $data['pharmacy']=$this->home_model->pharmacy();
		$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
				$data['firstname']=$this->input->post('firstname');
				$data['lastname']=$this->input->post('lastname');
				$data['location']=$this->input->post('loc');
				$data['email']=$this->input->post('emailid');
				$data['passconf']=$this->input->post('passconf');
				$data['passconf']=$this->input->post('currpharmacy');
				$data['securityanswer']=$this->input->post('securityanswer');
				$data['healthcard']=$this->input->post('healthcard');
				$data['accept']=$this->input->post('healthcard');
	  
			$this->form_validation->set_rules('firstname', 'First Name', 'callback_firstname_check');
			$this->form_validation->set_rules('lastname', 'Last Name', 'callback_lastname_check');
			$this->form_validation->set_rules('loc', 'Location', 'callback_loc_check');
			$this->form_validation->set_rules('emailid', 'E-mail id', 'required|valid_email');
			$this->form_validation->set_rules('pwd', 'Password', 'trim|required|min_length[7]|max_length[20]|required|matches[passconf]');
			$this->form_validation->set_rules('passconf', 'Confirm Password', 'trim|required');
			$this->form_validation->set_rules('currpharmacy', 'current pharmacy', 'trim|required');
			$this->form_validation->set_rules('securityanswer', 'Security Answer', 'required');
			
		//  $this->form_validation->set_rules('accept', 'I Accept. ', 'required');
	  
				
				if($this->form_validation->run() == FALSE)
				{
					$this->load->view('header',$data);
					$this->load->view('createaccount',$data);
					$this->load->view('footer',$data);
				}
				elseif($this->input->post('addBtn')=="Submit")
				{
						$insert['firstname']=$this->input->post('firstname');
						$insert['lastname']=$this->input->post('lastname');
						$insert['location']=$this->input->post('loc');
						$email=$insert['email']=$this->input->post('emailid');
						$password=$insert['password']=base64_encode($this->input->post('pwd'));
						$insert['pharmacyNo']=$this->input->post('currpharmacy');
						$insert['securityquest']=$this->input->post('ques');
						$insert['securityanswer']=md5($this->input->post('securityanswer'));
						$insert['dob']=$this->input->post('year')."-".$this->input->post('month')."-".$this->input->post('date');
						$insert['date']=date('Y-m-d');
						$insert['sex']=$this->input->post('sex');
						$insert['cardno']=$this->input->post('healthcard');
			
						    
						   $mb= $this->db->get_where('member', array('email' => $email));
						   if(($mb->num_rows)>0)
						   {
							   $data['message']="email id is already existed";
							   $this->load->view('header',$data);
					           $this->load->view('signin',$data);
					           $this->load->view('footer',$data);
							   
						   }
						   else
						   {
						   
							$data['member']=$this->db->insert('member', $insert); 
							if(!empty($data['member']))
							{
								
							$this->email->from('pharmacy@gmail.com', 'MSP');
							$this->email->to($email);
							$this->email->cc('renjith.kumar@primemoveindia.com');
							
							$message="Your account is created in MSP";
							$this->email->subject('Account confirmation from Main Street Pharmacy');
							$this->email->message($message);

							$this->email->send();
							
							$insert['email']=$email;
							$insert['password']=$password;
							   
							$login=$this->home_model->signin($insert);
							$no=$login->num_rows;
							
							   
							   if($no>0)
							   {
								   
								   foreach($login->result() as $log)
								   {
								   }
								 
								  $this->session->set_userdata('memberid', $log->memberid);
								  $this->session->set_userdata('name', $log->firstname);
								  						  
								}
		                    }
							$this->load->view('header',$data);
							$this->load->view('accountconfirmation',$data);
							$this->load->view('footer',$data);
					        }
					
					
				}
		
	}
	
	
	function getkey()
	{
	   
	   
      	$id=$this->input->post('queryString');
        
	if($id!="Pharmacy Name")
	{
    	$getp=$this->home_model->getpharmacy($id);
	foreach($getp->result() as $get)
	{
		
	}
	 $data['city']=$get->city;
	 $data['state']=$get->state;
	 $data['pharmacyphone']=$get->phone;
			
		    }
	}
	
	function cosultation()
	{
	$data['base'] = $this->base;
	$data['css'] = $this->css;
	$data['menu']="info";
	$data['cart_count']=$this->home_model->count_cart_items();
	$data['submenu']=$this->home_model->submenu();
	$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
	$this->load->view('header',$data);
	$this->load->view('cosultation',$data);
	$this->load->view('footer',$data);
	}
	
	function contactus()
	{
	$data['base'] = $this->base;
     	$data['css'] = $this->css;
	$data['menu']="";
	$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
	$data['pharmacy'] =$this->home_model->pharmacy();
	$data['cart_count']=$this->home_model->count_cart_items();
	$data['submenu']=$this->home_model->submenu();
    	$this->load->view('header',$data);
	$this->load->view('contactus',$data);
	$this->load->view('footer',$data);
	}
	
	function privacy()
	{
		 $data['base'] = $this->base;
	     $data['css'] = $this->css;
		 $data['menu']="";
		 $data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
					   <script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
		$data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
	    $this->load->view('header',$data);
		$this->load->view('privacy',$data);
		$this->load->view('footer',$data);
	}
	
	
	
	
	function check($str)
	{
		$values=array("First name","Initial","Last name","Date of birth","Phone number","Select your medication","Prescriber’s Name","Rx number","Pharmacy Name","E-mail id","Select your Pharmacy");
	
		if(in_array($str,$values))
		{
			$this->form_validation->set_message('check', 'The %s field is required');
			return FALSE;
			
		}
		else
		{
			return TRUE;
			
		}
	
		
	}
	//Member signin functions follows   
	
	function firstname_check($str)
	{
		if ($str == 'First name')
		{
			$this->form_validation->set_message('firstname_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function lastname_check($str)
	{
		if ($str == 'Last name')
		{
			$this->form_validation->set_message('lastname_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function loc_check($str)
	{
		if ($str == 'Location')
		{
			$this->form_validation->set_message('loc_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function emailid_check($str)
	{
		if ($str == 'E-mail id')
		{
			$this->form_validation->set_message('emailid_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function pwd_check($str)
	{
		if ($str == 'Password')
		{
			$this->form_validation->set_message('pwd_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	
	function passconf_check($str)
	{
		if ($str == 'Confirm Password')
		{
			$this->form_validation->set_message('passconf_check', 'The %s field must fill');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	function signout()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="home";
	   $data['pr_image']=$this->home_model->product_image();
	   
		$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>
						<script type=\"text/javascript\" src=\"".$this->base."js/slider.js\"></script>";
	   	 $data['msg']='0';
	   $data['cart_count']=$this->home_model->count_cart_items();
	   $data['submenu']=$this->home_model->submenu();
	   $this->form_validation->set_rules('email','Email','required|valid_email');
		if($this->form_validation->run()==TRUE)
		{
			$filename=get_filenames($this->base."newsletter/");
			//print_r($filename);
			$data=file_get_contents($this->base."newsletter/".$filename[0]);
			$name="newsletter.pdf";
			force_download($name,$data);
			$data['val']="1";
		}
		else
		{
			$data['val']="0";
			/*echo "<script type=\"text/javascript\">alert(\"Please specify proper email id\")</script>";*/
		 }
	  
		$this->session->unset_userdata('memberid');
		$this->session->unset_userdata('name');
		
		$this->load->view('header',$data);
		$this->load->view('home_new',$data);
		$this->load->view('footer',$data);
		
	}

/* Terms and condition */

function terms()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="";
	  
		$data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
		$this->load->view('header',$data);
		$this->load->view('terms',$data);
		$this->load->view('footer',$data);
		
	}
/* Shipping Information */

function shipping()
	{
	   $data['base'] = $this->base;
	   $data['css'] = $this->css;
	   $data['menu']="";
	  $data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
		
		$this->load->view('header',$data);
		$this->load->view('shipping',$data);
		$this->load->view('footer',$data);
		
	}
function store_locator($id="")
{
	$data['base'] = $this->base;
	$data['css'] = $this->css;
	$data['menu']="home";
	$data['category']=$this->home_model->category();
	$data['pharmacys']=$this->home_model->pharmacy();
	$data['cart_count']=$this->home_model->count_cart_items();
	$data['submenu']=$this->home_model->submenu();
/*
	$data['state'] = $this->home_model->states();
	$data['city'] = $this->home_model->city($id);
*/
	$this->load->view('header',$data);
	$this->load->view('store_locator',$data);
	$this->load->view('footer',$data);
}
function store_details($pharmacyId)
{
	$data['base'] = $this->base;
	$data['css'] = $this->css;
	$data['menu']="home";
		$data['pharmacy_details']=$this->home_model->get_pharmacy_details($pharmacyId);
		$data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
		//print_r($data['pharmacy_details']);
	$this->load->view('header',$data);
	$this->load->view('store_details',$data);
	$this->load->view('footer',$data);
}
function view_all_comments($id=0,$start=0)
{
	$data['base'] = $this->base;
	$data['css'] = $this->css;
	$data['menu']="";
	$data['id']=$id;
	

		$this->limit=4;
		$page['start']=intval($start);
		$page['count']=0; 
		$page['limit'] = $this->limit; 
		$data['feedback_lst']=$this->home_model->list_rec('review',$page); 
		$data['cart_count']=$this->home_model->count_cart_items();
		$data['submenu']=$this->home_model->submenu();
		//print_r($data['feedback_lst']['result']);
		$page['count']=1; 
		$data['feedback_count']=$this->home_model->list_rec('review',$page);
		
		$config['prev_link'] = '&lsaquo;&lsaquo; Previous'; 
		$config['next_link'] = 'Next &rsaquo;&rsaquo;'; 
		 
		$config['base_url']=$this->base.'index.php/home/view_all_comments/'.$id.'/'; 
		$config['total_rows']=$data['feedback_count']['totalrows'];
		$config['per_page']=$this->limit; 
		$this->pagination->initialize($config);
		$data['link']= $this->pagination->create_links();
	//	print_r($data['link']);
	
	$this->load->view('header',$data);
	$this->load->view('feedback',$data);
	$this->load->view('footer',$data);
}
function external_search($start="",$s="")
{
	$data['base']=$this->base;
	$data['css']=$this->css;
	$data['cart_count']=$this->home_model->count_cart_items();
	$data['menu']="home";
	$start1=$start+1;
	//$this->form_validation->set_rules('search_field','search_field','required');
	$this->load->library('pagination');
	if(empty($s)==TRUE)
	{
		$search=$this->input->post('search_field');
	}
	else
	{
		$search=$s;
	}
		$config['base_url']=$this->base.'index.php/home/external_search/';
		$config['prev_link'] = '&lsaquo;&lsaquo; Previous'; 
		$config['next_link'] = 'Next &rsaquo;&rsaquo;';
		$data['search']=$search;
		$products=$this->home_model->product_search_count($search);
		$data['products']=$this->home_model->product_search($search,intval($start),10);
		$config['total_rows']=count($products);
		$config['per_page']=10;
		$this->pagination->initialize($config); 
		$data['page']=$this->pagination->create_links();
	
	$this->load->view('header',$data);
	$this->load->view('search_list',$data);
	$this->load->view('footer',$data);
	
}
function search_error($str="",$start=0)
{
	$data['base']=$this->base;
	$data['css']=$this->css;
	$data['extra']="<script type=\"text/javascript\" src=\"".$this->base."js/jquery.js\"></script>";
	$data['cart_count']=$this->home_model->count_cart_items();
	$this->form_validation->set_rules('search','search','required');
	$search=$this->input->post('search');
	$data['menu']="home";
	$data['str']=$str;
	$data['search']=$search;
	if($this->form_validation->run()==TRUE)
	{
		$config['base_url']=$this->base.'index.php/home/external_search/';
		$config['prev_link'] = '&lsaquo;&lsaquo; Previous'; 
		$config['next_link'] = 'Next &rsaquo;&rsaquo;';
		$products=$this->home_model->product_search_count($search); 
		$data['products']=$this->home_model->product_search($search,$start,10);
		$config['total_rows']=count($products);
		$config['per_page']=10;
		$this->pagination->initialize($config); 
		$data['page']=$this->pagination->create_links();
		if(!empty($data['products'])==TRUE)
		{
				$this->load->view('header',$data);
				$this->load->view('search_list',$data);
				$this->load->view('footer',$data);
		}
	}
	$this->load->view('header',$data);
	$this->load->view('drug_search',$data);
	$this->load->view('footer',$data);
}
function drug_suggestion()
{
	$data['base']=$this->base;
	$data['css']=$this->css;
	$data['menu']="home";
	$this->form_validation->set_rules('product_name','Product Name','required');
	$this->form_validation->set_rules('Product_sugg','Product Description','required');
	$this->form_validation->set_rules('firstname','First Name','required');
	$this->form_validation->set_rules('lastname','Last Name','required');	
	$this->form_validation->set_rules('email','Email Address','required|valid_email');
	$insert_array['memberid']=intval($this->session->userdata('memberid'));
	$insert_array['drugname']=$this->input->post('product_name');
	$insert_array['drugdescription']=$this->input->post('Product_sugg');
	$insert_array['firstname']=$this->input->post('firstname');
	$insert_array['lastname']=$this->input->post('lastname');
	$insert_array['email']=$this->input->post('email');
/***********************************email**************************************/
/*
	$message="<html xmlns=\"http://www.w3.org/1999/xhtml\">
		<head>
		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
		<title>Product Suggestion/title>
		</head>
		<body>

			<table width=\"500\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
			  <tr>
			    <td>Drug Description :</td>
			    <td>".$this->input->post('Product_sugg')."</td>
			  </tr>
			  <tr>
			    <td>Name :</td>
			    <td>".$this->input->post('firstname')." ".$this->input->post('lastname')."</td>
			  </tr>
			  <tr>
			    <td>email :</td>
			    <td>".$this->input->post('email')."</td>
			</table>
		</body>
	</html>";
*/
	$message="
				<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">
				<html xmlns=\"http://www.w3.org/1999/xhtml\">
					<head>
						<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/>
						<title>Product Suggestion/title>
						<style type=\"text/css\">
							#wrapper{width:600px; height:417px; margin:0; padding:0; border:solid 1px #CCCCCC;} 
							#header{ width:600px; height:80px; background: url(".$data['base']."images/header_bg.jpg) repeat-x; border-bottom:solid 1px #CCCCCC;}
							#createaccount_head {width:580px; height:30px; float:left; color:#4d5559; font-family:Georgia; font-size:20px; padding-left:20px; padding-top:5px; border-bottom:dotted 1px #CCCCCC;}
							#content_section{width:580px; height:160px; float:left; color:#4d5559; font-family:Georgia; font-size:14px; padding-left:20px; padding-top:30px;}
							#footer{ height:100px; width:584px; padding-left:10px; float:left;font-family:Arial; font-size:10px; color:#ffffff; margin-left:3px; background-color:#CCCCCC; padding-top:5px; padding-bottom:2px;}
							#footer{ height:100px; width:584px; padding-left:10px; float:left;font-family:Arial; font-size:10px; color:#ffffff; margin-left:3px; background-color:#CCCCCC; padding-top:5px; padding-bottom:2px;}
							#footer span{font-family:Arial; font-size:11px; color:#ffffff; font-weight:bold;}
						</style>
					</head>
					<body>
						<div id='wrapper'>
							<div id='header'><img src='".$data['base']."images/logo.jpg'/></div>
							<div id='createaccount_head'>Prescription Suggestion</div>
							<div id='content_section'>
								<table width='570' border='0' cellpadding='0' cellspacing='0'>
									<tr>
										<td width='150' height='30 valign='top'>Drugname :</td>
										<td width='448' align='left' valign='top'>".$this->input->post('product_name')."&nbsp;</td>
									</tr>
									<tr>
										<td width='150' height='50' valign='top'>Drug description :</td>
										<td valign='top'>".$this->input->post('Product_sugg')."&nbsp;</td>
									</tr>
									<tr>
										<td width='150' height='30' valign='top'>Name :</td>
										<td valign='top'>".$this->input->post('firstname')." ".$this->input->post('lastname')."&nbsp;</td>
									</tr>
									<tr>
										<td width='150' height='30' valign='top'>Email id :</td>
										<td valign='top'>".$this->input->post('email')."&nbsp;</td>
									</tr>
								</table>
							</div>
							<div id='footer'>
								<span>www.Main Street Pharmacy.com</span><br /><br />
								'Please consider the planet before printing this email'. to your outgoing email signature. 
								You'll save a tree, save the clutter and maybe help save your friends from an avalanche of needless paper.
							</div>
						</div>
					</body>
				</html>";	

//echo $message;	
	$to='renjith.kumar@primemoveindia.com,geethu.maria@primemoveindia.com';
	$name=$this->input->post('firstname')." ".$this->input->post('lastname');
	$subject='Product Suggestion';
	$from="www.mainstreetpharmacys.com";
/*********************************************************************************/
	if($this->input->post('check1')=="checked")
	{
		$insert_array['emailcontact']=1;
	}
	else
	{
		$insert_array['emailcontact']=0;
	}
	$tablename="drugsuggestion";
	if($this->form_validation->run()==TRUE)
	{
		$data['res']=$this->home_model->insertquery($insert_array,$tablename);
		$this->email($to,$subject,$message,$from,$name);
	}
	$this->load->view('drug_suggestion',$data);
}
function email($recipient,$subject,$message,$from,$name)
{
	$this->load->library('email');
	//$config['protocol'] = 'sendmail';
	//$config['mailpath'] = '/usr/sbin/sendmail';
	//$config['charset'] = 'iso-8859-1';
	//$config['wordwrap'] = TRUE;
	$config['mailtype'] = "html";
	$this->email->initialize($config);

	$this->email->from($from,$name);
	$this->email->to($recipient); 
	$this->email->cc('manju.primemove@gmail.com'); 
	$this->email->reply_to($from,$name);
	$this->email->subject($subject);
	$this->email->message($message);
	$this->email->send();
}
function viewmore_newproducts($start=0)
{
	$data['base']=$this->base;
	$data['css']=$this->css;
	$data['menu']="home";
	$data['category']=$this->home_model->category();
	$data['cart_count']=$this->home_model->count_cart_items();
	$data['submenu']=$this->home_model->submenu();
	$latest_date=$this->home_model->latest_date();
//	echo $latest_date->imagedate;
	
		$this->limit=15;
		$page['start']=intval($start);
		$page['limit'] = $this->limit; 
		$data['product_image']=$this->home_model->viewmore_newproducts($latest_date->imagedate,$page);
		//print_r($data['product_image']);

		
		$config['prev_link'] = '&lsaquo;&lsaquo; Previous'; 
		$config['next_link'] = 'Next &rsaquo;&rsaquo;'; 
		 
		$config['base_url']=$this->base.'index.php/home/viewmore_newproducts/'; 
		$config['total_rows']=$data['product_image']['totalrows'];
		$config['per_page']=$this->limit; 
		$this->pagination->initialize($config);
		$data['link']= $this->pagination->create_links();
		//print_r($data['link']);
		
	$this->load->view('header',$data);
	$this->load->view('view_more_newproducts',$data);
	$this->load->view('footer',$data);
}
}
/* End of file home.php */
/* Location: ./system/application/controllers/home.*/
