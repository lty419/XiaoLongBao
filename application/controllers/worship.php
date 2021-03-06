<?php

/**
  * A controller for the Sunday worship videos.
  * CHINESE ONLY
  */
class Worship extends CI_Controller 
{
	function __construct()
	{
		// Call the Controller constructor
		parent::__construct();
		$this->load->helper("url");
		$this->load->model('video_model', 'video');
		$this->load->library("pagination");
	}

	public function index($lang = 'ch')
	{
	  $this->page();
	}

	public function page($page = 0, $lang = 'ch')
	{
	  if ( ! file_exists('application/views/'.$lang.'/worship/worship.php'))
	  {
	    show_404();
	  }
	  
	  // pagination
	  $config = array();
	  $config["base_url"] = site_url()."/worship/page";
	  $config["total_rows"] = $this->video->get_video_count();
	  $config["per_page"] = 5;
	  //$config["uri_segment"] = 3;
	  $this->pagination->initialize($config);
	  
	  $data['videos'] = $this->video->get_sunday_videos($config["per_page"], $page);
	  $data["links"] = $this->pagination->create_links(); // TODO, make it looks better
	  
	  $this->load->view('templates/header_ch');
	  $this->load->view($lang.'/worship/worship', $data);
	  $this->load->view('templates/footer');
	}
	
	public function video($id = NULL)
	{
	  if ( ! file_exists('application/views/ch/worship/video.php'))
	  {
	    // Whoops, we don't have a page for that!
	    show_404();
	  }
	  
	  $data['video'] = $this->video->get_video($id);

	  $this->load->library('javascript_plugins');
	  $plugins = $this->javascript_plugins;
	  $footer_data['js_plugins'] = $plugins->generate(array($plugins::FlowPlayer));
	  
	  $this->load->view('templates/header_ch');
	  $this->load->view('ch/worship/video', $data);
	  $this->load->view('templates/footer', $footer_data);
	}
	
	public function audio($id = NULL)
	{
	  if ( ! file_exists('application/views/ch/worship/audio.php'))
	  {
	    // Whoops, we don't have a page for that!
	    show_404();
	  }
	   
	  $data['video'] = $this->video->get_video($id);
	  $this->load->library('javascript_plugins');
	  $plugins = $this->javascript_plugins;
	  $footer_data['js_plugins'] = $plugins->generate(array($plugins::FlowPlayer));
	   
	  $this->load->view('templates/header_ch');
	  $this->load->view('ch/worship/audio', $data);
	  $this->load->view('templates/footer', $footer_data);
	}
	
	public function direct_download($file)
	{
	  $file_url = base_url().'/audios/'.$file;

	  if ( ! file_exists('audios/'.$file))
	  {
	    show_404();
	  }

	  header ("Content-type: octet/stream");
	  header ("Content-disposition: attachment; filename=".$file.";");
	  header ("Content-Length: ".filesize($file));
	  readfile($file_url);
	  exit;
	}

}
?>