<?php /* Smarty version 2.6.26, created on 2009-11-14 23:10:32
         compiled from index.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>


<?php $_from = $this->_tpl_vars['public']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['public']):
?>
<?php $this->assign('i', $this->_tpl_vars['i']+1); ?>
		<div class="container">  
	 <table align="center" id="public">
	 <th><a href='javascript:void(0)' onclick='$("#<?php echo $this->_tpl_vars['public']['id']; ?>
").toggle("slow");'>Hide<?php echo $this->_tpl_vars['i']; ?>
</a></th>
	 <tr id="<?php echo $this->_tpl_vars['public']['id']; ?>
"><td>
	 
	 <div class="error" id="<?php echo $this->_tpl_vars['public']['id']; ?>
">
	 <div class='bubble'>
	<div class='rounded'>
	<div id='twitter_photo'><img  src='<?php echo $this->_tpl_vars['public']['user']['profile_image_url']; ?>
'/></div>
        <h2 class="alt"><?php echo $this->_tpl_vars['public']['text']; ?>
</h3>
	</div>
	<div>
	<div class="notice">
	<div class="success">
		<div class='rounded'>
	<blockquote>
	  <p>
	  <h3 >Username: <?php echo $this->_tpl_vars['public']['user']['name']; ?>
</h3><br />
	  <h3>UserScreenName: <a href='http://twitter.com/<?php echo $this->_tpl_vars['public']['user']['screen_name']; ?>
'><?php echo $this->_tpl_vars['public']['user']['screen_name']; ?>
</a></h3><br />
	  <?php if ($this->_tpl_vars['public']['user']['url']): ?><h3 >Url <a href='<?php echo $this->_tpl_vars['public']['user']['url']; ?>
'><?php echo $this->_tpl_vars['public']['user']['url']; ?>
</a> </h3><br /><?php endif; ?>
	  <h3>Location: <?php echo $this->_tpl_vars['public']['user']['location']; ?>
</h3><br />
	  <h3>Id: <?php echo $this->_tpl_vars['public']['user']['id']; ?>
</strong><br />
	  <h3 style="color:red;">Followers: <?php echo $this->_tpl_vars['public']['user']['followers_count']; ?>
</h3>
	   <?php echo $this->_tpl_vars['i']-1; ?>

	  <img src='templates/example1.png'/>
	  <br />
	 </p>
	</blockquote>
	<div>
	</div>
	</div>
	</div>	
	  </div>
	  </div></td></tr></table>

<?php endforeach; endif; unset($_from); ?>



<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>