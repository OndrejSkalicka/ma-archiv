<?php /* Smarty version 2.6.14, created on 2013-05-18 10:21:57
         compiled from head.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'truncate', 'head.tpl', 12, false),)), $this); ?>
<div class="archivar">
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td style="width: 240px;"> 
        <img src="img/archivar/archivist_r.jpg" height="90" width="113" alt=""> &nbsp;&nbsp;
      </td>
      <td style="width: 120px;">        
        <table style="width: 120px;">
          <?php unset($this->_sections['topFive']);
$this->_sections['topFive']['name'] = 'topFive';
$this->_sections['topFive']['loop'] = is_array($_loop=($this->_tpl_vars['topUsers'])) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['topFive']['max'] = (int)'5';
$this->_sections['topFive']['start'] = (int)'1';
$this->_sections['topFive']['show'] = true;
if ($this->_sections['topFive']['max'] < 0)
    $this->_sections['topFive']['max'] = $this->_sections['topFive']['loop'];
$this->_sections['topFive']['step'] = 1;
if ($this->_sections['topFive']['start'] < 0)
    $this->_sections['topFive']['start'] = max($this->_sections['topFive']['step'] > 0 ? 0 : -1, $this->_sections['topFive']['loop'] + $this->_sections['topFive']['start']);
else
    $this->_sections['topFive']['start'] = min($this->_sections['topFive']['start'], $this->_sections['topFive']['step'] > 0 ? $this->_sections['topFive']['loop'] : $this->_sections['topFive']['loop']-1);
if ($this->_sections['topFive']['show']) {
    $this->_sections['topFive']['total'] = min(ceil(($this->_sections['topFive']['step'] > 0 ? $this->_sections['topFive']['loop'] - $this->_sections['topFive']['start'] : $this->_sections['topFive']['start']+1)/abs($this->_sections['topFive']['step'])), $this->_sections['topFive']['max']);
    if ($this->_sections['topFive']['total'] == 0)
        $this->_sections['topFive']['show'] = false;
} else
    $this->_sections['topFive']['total'] = 0;
if ($this->_sections['topFive']['show']):

            for ($this->_sections['topFive']['index'] = $this->_sections['topFive']['start'], $this->_sections['topFive']['iteration'] = 1;
                 $this->_sections['topFive']['iteration'] <= $this->_sections['topFive']['total'];
                 $this->_sections['topFive']['index'] += $this->_sections['topFive']['step'], $this->_sections['topFive']['iteration']++):
$this->_sections['topFive']['rownum'] = $this->_sections['topFive']['iteration'];
$this->_sections['topFive']['index_prev'] = $this->_sections['topFive']['index'] - $this->_sections['topFive']['step'];
$this->_sections['topFive']['index_next'] = $this->_sections['topFive']['index'] + $this->_sections['topFive']['step'];
$this->_sections['topFive']['first']      = ($this->_sections['topFive']['iteration'] == 1);
$this->_sections['topFive']['last']       = ($this->_sections['topFive']['iteration'] == $this->_sections['topFive']['total']);
?>
            <tr>
              <td><?php echo $this->_sections['topFive']['index']; ?>
.</td>
              <td><?php echo ((is_array($_tmp=$this->_tpl_vars['topUsers'][$this->_sections['topFive']['index']]['0'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 12) : smarty_modifier_truncate($_tmp, 12)); ?>
</td> 
              <td align="right"><?php echo $this->_tpl_vars['topUsers'][$this->_sections['topFive']['index']]['1']; ?>
x</td>
            </tr>
          <?php endfor; endif; ?>
        </table>
      </td>
      <td style="width: 80px; text-align: center;">
        <strong>TOP 10<br />
        Archiváøi</strong><br />
        <span style="font-style: italic;font-size: 10px;">(bez adminù)</span>
      </td>
      <td style="width: 120px;">
        <table style="width: 120px;">
          <?php unset($this->_sections['topFive_5']);
$this->_sections['topFive_5']['name'] = 'topFive_5';
$this->_sections['topFive_5']['loop'] = is_array($_loop=($this->_tpl_vars['topUsers'])) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['topFive_5']['max'] = (int)'5';
$this->_sections['topFive_5']['start'] = (int)'6';
$this->_sections['topFive_5']['show'] = true;
if ($this->_sections['topFive_5']['max'] < 0)
    $this->_sections['topFive_5']['max'] = $this->_sections['topFive_5']['loop'];
$this->_sections['topFive_5']['step'] = 1;
if ($this->_sections['topFive_5']['start'] < 0)
    $this->_sections['topFive_5']['start'] = max($this->_sections['topFive_5']['step'] > 0 ? 0 : -1, $this->_sections['topFive_5']['loop'] + $this->_sections['topFive_5']['start']);
else
    $this->_sections['topFive_5']['start'] = min($this->_sections['topFive_5']['start'], $this->_sections['topFive_5']['step'] > 0 ? $this->_sections['topFive_5']['loop'] : $this->_sections['topFive_5']['loop']-1);
if ($this->_sections['topFive_5']['show']) {
    $this->_sections['topFive_5']['total'] = min(ceil(($this->_sections['topFive_5']['step'] > 0 ? $this->_sections['topFive_5']['loop'] - $this->_sections['topFive_5']['start'] : $this->_sections['topFive_5']['start']+1)/abs($this->_sections['topFive_5']['step'])), $this->_sections['topFive_5']['max']);
    if ($this->_sections['topFive_5']['total'] == 0)
        $this->_sections['topFive_5']['show'] = false;
} else
    $this->_sections['topFive_5']['total'] = 0;
if ($this->_sections['topFive_5']['show']):

            for ($this->_sections['topFive_5']['index'] = $this->_sections['topFive_5']['start'], $this->_sections['topFive_5']['iteration'] = 1;
                 $this->_sections['topFive_5']['iteration'] <= $this->_sections['topFive_5']['total'];
                 $this->_sections['topFive_5']['index'] += $this->_sections['topFive_5']['step'], $this->_sections['topFive_5']['iteration']++):
$this->_sections['topFive_5']['rownum'] = $this->_sections['topFive_5']['iteration'];
$this->_sections['topFive_5']['index_prev'] = $this->_sections['topFive_5']['index'] - $this->_sections['topFive_5']['step'];
$this->_sections['topFive_5']['index_next'] = $this->_sections['topFive_5']['index'] + $this->_sections['topFive_5']['step'];
$this->_sections['topFive_5']['first']      = ($this->_sections['topFive_5']['iteration'] == 1);
$this->_sections['topFive_5']['last']       = ($this->_sections['topFive_5']['iteration'] == $this->_sections['topFive_5']['total']);
?>
            <tr> 
              <td><?php echo $this->_sections['topFive_5']['index']; ?>
.</td>
              <td><?php echo ((is_array($_tmp=$this->_tpl_vars['topUsers'][$this->_sections['topFive_5']['index']]['0'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 12) : smarty_modifier_truncate($_tmp, 12)); ?>
</td> 
              <td align="right"><?php echo $this->_tpl_vars['topUsers'][$this->_sections['topFive_5']['index']]['1']; ?>
x</td>
            </tr>            
          <?php endfor; endif; ?>
        </table>
      </td>
      <td style="width: 240px;">
        &nbsp;
      </td>
    </tr>
  </table>	
</div> <!-- //archivar -->