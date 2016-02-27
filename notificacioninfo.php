<?php

// Global variable for table object
$notificacion = NULL;

//
// Table class for notificacion
//
class cnotificacion extends cTable {
	var $id_notificacion;
	var $obs_noti;
	var $st_noti;
	var $id_afiliado;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'notificacion';
		$this->TableName = 'notificacion';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->DetailView = FALSE; // Allow detail view
		$this->ShowMultipleDetails = FALSE; // Show multiple details
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// id_notificacion
		$this->id_notificacion = new cField('notificacion', 'notificacion', 'x_id_notificacion', 'id_notificacion', '`id_notificacion`', '`id_notificacion`', 3, -1, FALSE, '`id_notificacion`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_notificacion->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_notificacion'] = &$this->id_notificacion;

		// obs_noti
		$this->obs_noti = new cField('notificacion', 'notificacion', 'x_obs_noti', 'obs_noti', '`obs_noti`', '`obs_noti`', 201, -1, FALSE, '`obs_noti`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['obs_noti'] = &$this->obs_noti;

		// st_noti
		$this->st_noti = new cField('notificacion', 'notificacion', 'x_st_noti', 'st_noti', '`st_noti`', '`st_noti`', 16, -1, FALSE, '`st_noti`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->st_noti->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['st_noti'] = &$this->st_noti;

		// id_afiliado
		$this->id_afiliado = new cField('notificacion', 'notificacion', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_afiliado'] = &$this->id_afiliado;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`notificacion`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		$allow = EW_USER_ID_ALLOW;
		switch ($id) {
			case "add":
			case "copy":
			case "gridadd":
			case "register":
			case "addopt":
				return (($allow & 1) == 1);
			case "edit":
			case "gridedit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return (($allow & 4) == 4);
			case "delete":
				return (($allow & 2) == 2);
			case "view":
				return (($allow & 32) == 32);
			case "search":
				return (($allow & 64) == 64);
			default:
				return (($allow & 8) == 8);
		}
	}

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . preg_replace('/^SELECT\s([\s\S]+)?\*\sFROM/i', "", $sSql);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`notificacion`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "", $rsold = NULL) {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			if (array_key_exists('id_notificacion', $rs))
				ew_AddFilter($where, ew_QuotedName('id_notificacion') . '=' . ew_QuotedValue($rs['id_notificacion'], $this->id_notificacion->FldDataType));
		}
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")
			$sql .= $filter;
		else
			$sql .= "0=1"; // Avoid delete
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`id_notificacion` = @id_notificacion@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_notificacion->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_notificacion@", ew_AdjustSql($this->id_notificacion->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "notificacionlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "notificacionlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("notificacionview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("notificacionview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "notificacionadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("notificacionedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("notificacionadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("notificaciondelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_notificacion->CurrentValue)) {
			$sUrl .= "id_notificacion=" . urlencode($this->id_notificacion->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&amp;ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_notificacion"]; // id_notificacion

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_notificacion->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->id_notificacion->setDbValue($rs->fields('id_notificacion'));
		$this->obs_noti->setDbValue($rs->fields('obs_noti'));
		$this->st_noti->setDbValue($rs->fields('st_noti'));
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_notificacion
		// obs_noti
		// st_noti
		// id_afiliado
		// id_notificacion

		$this->id_notificacion->ViewValue = $this->id_notificacion->CurrentValue;
		$this->id_notificacion->ViewCustomAttributes = "";

		// obs_noti
		$this->obs_noti->ViewValue = $this->obs_noti->CurrentValue;
		$this->obs_noti->ViewCustomAttributes = "";

		// st_noti
		if (strval($this->st_noti->CurrentValue) <> "") {
			switch ($this->st_noti->CurrentValue) {
				case $this->st_noti->FldTagValue(1):
					$this->st_noti->ViewValue = $this->st_noti->FldTagCaption(1) <> "" ? $this->st_noti->FldTagCaption(1) : $this->st_noti->CurrentValue;
					break;
				case $this->st_noti->FldTagValue(2):
					$this->st_noti->ViewValue = $this->st_noti->FldTagCaption(2) <> "" ? $this->st_noti->FldTagCaption(2) : $this->st_noti->CurrentValue;
					break;
				default:
					$this->st_noti->ViewValue = $this->st_noti->CurrentValue;
			}
		} else {
			$this->st_noti->ViewValue = NULL;
		}
		$this->st_noti->ViewCustomAttributes = "";

		// id_afiliado
		$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
		if (strval($this->id_afiliado->CurrentValue) <> "") {
			$sFilterWrk = "`id_afiliado`" . ew_SearchString("=", $this->id_afiliado->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id_afiliado`, `dociden_afiliado` AS `DispFld`, `apell_afiliado` AS `Disp2Fld`, `nomb_afiliado` AS `Disp3Fld`, '' AS `Disp4Fld` FROM `afiliado`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}

		// Call Lookup selecting
		$this->Lookup_Selecting($this->id_afiliado, $sWhereWrk);
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->id_afiliado->ViewValue = $rswrk->fields('DispFld');
				$this->id_afiliado->ViewValue .= ew_ValueSeparator(1,$this->id_afiliado) . $rswrk->fields('Disp2Fld');
				$this->id_afiliado->ViewValue .= ew_ValueSeparator(2,$this->id_afiliado) . $rswrk->fields('Disp3Fld');
				$rswrk->Close();
			} else {
				$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
			}
		} else {
			$this->id_afiliado->ViewValue = NULL;
		}
		$this->id_afiliado->ViewCustomAttributes = "";

		// id_notificacion
		$this->id_notificacion->LinkCustomAttributes = "";
		$this->id_notificacion->HrefValue = "";
		$this->id_notificacion->TooltipValue = "";

		// obs_noti
		$this->obs_noti->LinkCustomAttributes = "";
		$this->obs_noti->HrefValue = "";
		$this->obs_noti->TooltipValue = "";

		// st_noti
		$this->st_noti->LinkCustomAttributes = "";
		$this->st_noti->HrefValue = "";
		$this->st_noti->TooltipValue = "";

		// id_afiliado
		$this->id_afiliado->LinkCustomAttributes = "";
		$this->id_afiliado->HrefValue = "";
		$this->id_afiliado->TooltipValue = "";

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->id_notificacion->Exportable) $Doc->ExportCaption($this->id_notificacion);
				if ($this->obs_noti->Exportable) $Doc->ExportCaption($this->obs_noti);
				if ($this->st_noti->Exportable) $Doc->ExportCaption($this->st_noti);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
			} else {
				if ($this->id_notificacion->Exportable) $Doc->ExportCaption($this->id_notificacion);
				if ($this->st_noti->Exportable) $Doc->ExportCaption($this->st_noti);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->id_notificacion->Exportable) $Doc->ExportField($this->id_notificacion);
					if ($this->obs_noti->Exportable) $Doc->ExportField($this->obs_noti);
					if ($this->st_noti->Exportable) $Doc->ExportField($this->st_noti);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
				} else {
					if ($this->id_notificacion->Exportable) $Doc->ExportField($this->id_notificacion);
					if ($this->st_noti->Exportable) $Doc->ExportField($this->st_noti);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) { 

		//require_once('phpjs/mailer/class.phpmailer.php');  
		if (!isset($conn)) $conn = ew_Connect();
		$sentencia = "UPDATE afiliado SET st_notificado='".$rsnew['st_noti']."' WHERE (id_afiliado='".$rsnew['id_afiliado']."')"; 
		CurrentPage()->setMessage("<strong><font color='red'>Registro de Afiliado: ".$rsnew['id_afiliado']." Actualizado</font></strong>"); 
		$row = $conn->Execute($sentencia); 
		$conn->Close();   
		$mail = new PHPMailer (true); 
		$mail->IsSMTP(); 
	try {      
	$mail->CharSet = "UTF-8"; //importante
	$mail -> AddAddress ('elcorreodemarvin@gmail.com','Ing.erick');
	$mail -> Subject = 'Prueba de correo de la app de boca junior';
	$mail -> Body = 'Este es un correo html, ud recibio una notificacion para que pague, morrongo mala paga';

	 //De parte de quien es el correo
	$mail->SetFrom("sistermagamedina@gmail.com", "se lo mando");              

	//para indicar que el correo es html
	$mail -> IsHTML (true);

	// si quieres agregar un encabeza personalizado
	// $mail->AddCustomHeader($headers);
	//acá viene la sección de autenticación:

		$mail->Host = 'ssl://smtp.gmail.com';
		$mail->Port = 465;//465;
		$mail->SMTPAuth = true;
		$mail->Username = 'mariagabriela3000@gmail.com';
		$mail->Password = '250781julio';
		$mail->Timeout=30;
		if(!$mail->Send()) {
			$emailnotificacion = "No se pudo enviar notificacion".$mail->ErrorInfo;
		}
		else {
			$emailnotificacion = "fino";
		}  
	  } catch (phpmailerException $e) {        
		  CurrentPage()->setMessage("Tipico ".$e->errorMessage()); //Errores de PhpMailer
		} catch (Exception $e) {
		  CurrentPage()->setMessage("cualquier cosa ".$e->getMessage()); //Errores de cualquier otra cosa.
		} 
	CurrentPage()->setMessage("<strong><font color='red'>Mensaje de envio: ".$emailnotificacion."</font></strong>"); 
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Lookup Selecting event
	function Lookup_Selecting($fld, &$filter) {

		// Enter your code here
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

		if ($this->PageID == "list" || $this->PageID == "view") { // List/View page only
			if ($this->st_noti->CurrentValue >= 2) {  //si esta negativa
				$this->st_noti->CellAttrs["style"] = "background-color: #ffcccc";
			} elseif ($this->st_noti->CurrentValue == 6) {
				$this->st_noti->CellAttrs["style"] = "background-color: #ffcc99";
			} elseif ($this->st_noti->CurrentValue == 8) {
				$this->st_noti->CellAttrs["style"] = "background-color: #ffccff";
			}
		}   
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
