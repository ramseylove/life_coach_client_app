<div class="right">
      <div class="maincontent">
        <h1>DashBoard</h1>
		<?php 
			$mesageArr = $this->session->flashdata('message');
			if($mesageArr)
			{
				foreach($mesageArr as $message)
				{
					echo $message;
				}
			}
		?>
        <div class="dashpage">
          <h2>Welcome to Admin Panel for survey application.</h2>
        </div>
      </div>
</div>