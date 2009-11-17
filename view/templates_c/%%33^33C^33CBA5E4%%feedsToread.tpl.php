<?php /* Smarty version 2.6.26, created on 2009-11-14 18:32:43
         compiled from feedsToread.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<div class="container">
<?php $_from = $this->_tpl_vars['read']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['read']):
?>

	 <table align="center">
	<th><a href='javascript:void(0)' onclick='$("#<?php echo $this->_tpl_vars['read']['count']; ?>
").toggle("slow");'>Hide</a><th>
	 <tr id="<?php echo $this->_tpl_vars['read']['count']; ?>
"><td>
	 <div class="error">
	 <div class='bubble'>
	<div class='rounded'>
        <h3 class="alt"><?php echo $this->_tpl_vars['read']['title']; ?>
</h3><br>
        <h3 class="alt"><?php echo $this->_tpl_vars['read']['url']; ?>
</h3><br>
	</div>
	<div>
	<div class="notice">
	<div class='rounded'>
	<blockquote>
	  <p>
	  <h4><?php echo $this->_tpl_vars['read']['content']; ?>
</h4>	 
	 </p>
	</blockquote>
	<div>
	</div>
	</div>	
	  </div>
	  </td></tr></table>

<?php endforeach; endif; unset($_from); ?>
</div>


<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "footer.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>