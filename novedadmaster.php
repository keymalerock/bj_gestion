<?php

// id_novedad
// id_afiliado
// obs_nov
// fe_nov
// estado_nov

?>
<?php if ($novedad->Visible) { ?>
<table id="t_novedad" class="ewGrid"><tr><td>
<table id="tbl_novedadmaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($novedad->id_novedad->Visible) { // id_novedad ?>
		<tr id="r_id_novedad">
			<td><?php echo $novedad->id_novedad->FldCaption() ?></td>
			<td<?php echo $novedad->id_novedad->CellAttributes() ?>>
<span id="el_novedad_id_novedad" class="control-group">
<span<?php echo $novedad->id_novedad->ViewAttributes() ?>>
<?php echo $novedad->id_novedad->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($novedad->id_afiliado->Visible) { // id_afiliado ?>
		<tr id="r_id_afiliado">
			<td><?php echo $novedad->id_afiliado->FldCaption() ?></td>
			<td<?php echo $novedad->id_afiliado->CellAttributes() ?>>
<span id="el_novedad_id_afiliado" class="control-group">
<span<?php echo $novedad->id_afiliado->ViewAttributes() ?>>
<?php echo $novedad->id_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($novedad->obs_nov->Visible) { // obs_nov ?>
		<tr id="r_obs_nov">
			<td><?php echo $novedad->obs_nov->FldCaption() ?></td>
			<td<?php echo $novedad->obs_nov->CellAttributes() ?>>
<span id="el_novedad_obs_nov" class="control-group">
<span<?php echo $novedad->obs_nov->ViewAttributes() ?>>
<?php echo $novedad->obs_nov->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($novedad->fe_nov->Visible) { // fe_nov ?>
		<tr id="r_fe_nov">
			<td><?php echo $novedad->fe_nov->FldCaption() ?></td>
			<td<?php echo $novedad->fe_nov->CellAttributes() ?>>
<span id="el_novedad_fe_nov" class="control-group">
<span<?php echo $novedad->fe_nov->ViewAttributes() ?>>
<?php echo $novedad->fe_nov->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($novedad->estado_nov->Visible) { // estado_nov ?>
		<tr id="r_estado_nov">
			<td><?php echo $novedad->estado_nov->FldCaption() ?></td>
			<td<?php echo $novedad->estado_nov->CellAttributes() ?>>
<span id="el_novedad_estado_nov" class="control-group">
<span<?php echo $novedad->estado_nov->ViewAttributes() ?>>
<?php echo $novedad->estado_nov->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
