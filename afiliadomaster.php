<?php

// dociden_afiliado
// apell_afiliado
// nomb_afiliado
// email_afiliado
// cel_afiliado
// genero_afiliado
// fe_afiliado
// telf_fijo_afiliado
// st_afiliado
// st_notificado

?>
<?php if ($afiliado->Visible) { ?>
<table id="t_afiliado" class="ewGrid"><tr><td>
<table id="tbl_afiliadomaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($afiliado->dociden_afiliado->Visible) { // dociden_afiliado ?>
		<tr id="r_dociden_afiliado">
			<td><?php echo $afiliado->dociden_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->dociden_afiliado->CellAttributes() ?>>
<span id="el_afiliado_dociden_afiliado" class="control-group">
<span<?php echo $afiliado->dociden_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->dociden_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->apell_afiliado->Visible) { // apell_afiliado ?>
		<tr id="r_apell_afiliado">
			<td><?php echo $afiliado->apell_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->apell_afiliado->CellAttributes() ?>>
<span id="el_afiliado_apell_afiliado" class="control-group">
<span<?php echo $afiliado->apell_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->apell_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->nomb_afiliado->Visible) { // nomb_afiliado ?>
		<tr id="r_nomb_afiliado">
			<td><?php echo $afiliado->nomb_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->nomb_afiliado->CellAttributes() ?>>
<span id="el_afiliado_nomb_afiliado" class="control-group">
<span<?php echo $afiliado->nomb_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->nomb_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->email_afiliado->Visible) { // email_afiliado ?>
		<tr id="r_email_afiliado">
			<td><?php echo $afiliado->email_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->email_afiliado->CellAttributes() ?>>
<span id="el_afiliado_email_afiliado" class="control-group">
<span<?php echo $afiliado->email_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->email_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->cel_afiliado->Visible) { // cel_afiliado ?>
		<tr id="r_cel_afiliado">
			<td><?php echo $afiliado->cel_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->cel_afiliado->CellAttributes() ?>>
<span id="el_afiliado_cel_afiliado" class="control-group">
<span<?php echo $afiliado->cel_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->cel_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->genero_afiliado->Visible) { // genero_afiliado ?>
		<tr id="r_genero_afiliado">
			<td><?php echo $afiliado->genero_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->genero_afiliado->CellAttributes() ?>>
<span id="el_afiliado_genero_afiliado" class="control-group">
<span<?php echo $afiliado->genero_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->genero_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->fe_afiliado->Visible) { // fe_afiliado ?>
		<tr id="r_fe_afiliado">
			<td><?php echo $afiliado->fe_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->fe_afiliado->CellAttributes() ?>>
<span id="el_afiliado_fe_afiliado" class="control-group">
<span<?php echo $afiliado->fe_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->fe_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->telf_fijo_afiliado->Visible) { // telf_fijo_afiliado ?>
		<tr id="r_telf_fijo_afiliado">
			<td><?php echo $afiliado->telf_fijo_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->telf_fijo_afiliado->CellAttributes() ?>>
<span id="el_afiliado_telf_fijo_afiliado" class="control-group">
<span<?php echo $afiliado->telf_fijo_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->telf_fijo_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->st_afiliado->Visible) { // st_afiliado ?>
		<tr id="r_st_afiliado">
			<td><?php echo $afiliado->st_afiliado->FldCaption() ?></td>
			<td<?php echo $afiliado->st_afiliado->CellAttributes() ?>>
<span id="el_afiliado_st_afiliado" class="control-group">
<span<?php echo $afiliado->st_afiliado->ViewAttributes() ?>>
<?php echo $afiliado->st_afiliado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
<?php if ($afiliado->st_notificado->Visible) { // st_notificado ?>
		<tr id="r_st_notificado">
			<td><?php echo $afiliado->st_notificado->FldCaption() ?></td>
			<td<?php echo $afiliado->st_notificado->CellAttributes() ?>>
<span id="el_afiliado_st_notificado" class="control-group">
<span<?php echo $afiliado->st_notificado->ViewAttributes() ?>>
<?php echo $afiliado->st_notificado->ListViewValue() ?></span>
</span>
</td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
