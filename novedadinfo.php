<?php

// Global variable for table object
$novedad = NULL;

//
// Table class for novedad
//
class cnovedad extends cTable {
	var $id_novedad;
	var $id_afiliado;
	var $obs_nov;
	var $fe_nov;
	var $estado_nov;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'novedad';
		$this->TableName = 'novedad';
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

		// id_novedad
		$this->id_novedad = new cField('novedad', 'novedad', 'x_id_novedad', 'id_novedad', '`id_novedad`', '`id_novedad`', 3, -1, FALSE, '`id_novedad`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_novedad->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_novedad'] = &$this->id_novedad;

		// id_afiliado
		$this->id_afiliado = new cField('novedad', 'novedad', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_afiliado'] = &$this->id_afiliado;

		// obs_nov
		$this->obs_nov = new cField('novedad', 'novedad', 'x_obs_nov', 'obs_nov', '`obs_nov`', '`obs_nov`', 201, -1, FALSE, '`obs_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['obs_nov'] = &$this->obs_nov;

		// fe_nov
		$this->fe_nov = new cField('novedad', 'novedad', 'x_fe_nov', 'fe_nov', '`fe_nov`', 'DATE_FORMAT(`fe_nov`, \'%Y/%m/%d\')', 135, 5, FALSE, '`fe_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fe_nov->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fe_nov'] = &$this->fe_nov;

		// estado_nov
		$this->estado_nov = new cField('novedad', 'novedad', 'x_estado_nov', 'estado_nov', '`estado_nov`', '`estado_nov`', 16, -1, FALSE, '`estado_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->estado_nov->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['estado_nov'] = &$this->estado_nov;
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

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "respuesta") {
			$sDetailUrl = $GLOBALS["respuesta"]->GetListUrl() . "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&id_novedad=" . $this->id_novedad->CurrentValue;
		}
		if ($sDetailUrl == "") {
			$sDetailUrl = "novedadlist.php";
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`novedad`";
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
				return TRUE;
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
	var $UpdateTable = "`novedad`";

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
			if (array_key_exists('id_novedad', $rs))
				ew_AddFilter($where, ew_QuotedName('id_novedad') . '=' . ew_QuotedValue($rs['id_novedad'], $this->id_novedad->FldDataType));
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
		return "`id_novedad` = @id_novedad@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_novedad->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_novedad@", ew_AdjustSql($this->id_novedad->CurrentValue), $sKeyFilter); // Replace key value
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
			return "novedadlist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "novedadlist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("novedadview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("novedadview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "novedadadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("novedadedit.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("novedadedit.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("novedadadd.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("novedadadd.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("novedaddelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_novedad->CurrentValue)) {
			$sUrl .= "id_novedad=" . urlencode($this->id_novedad->CurrentValue);
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
			$arKeys[] = @$_GET["id_novedad"]; // id_novedad

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
			$this->id_novedad->CurrentValue = $key;
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
		$this->id_novedad->setDbValue($rs->fields('id_novedad'));
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->obs_nov->setDbValue($rs->fields('obs_nov'));
		$this->fe_nov->setDbValue($rs->fields('fe_nov'));
		$this->estado_nov->setDbValue($rs->fields('estado_nov'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security, $gsLanguage;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// id_novedad
		// id_afiliado
		// obs_nov
		// fe_nov
		// estado_nov
		// id_novedad

		$this->id_novedad->ViewValue = $this->id_novedad->CurrentValue;
		$this->id_novedad->ViewCustomAttributes = "";

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

		// obs_nov
		$this->obs_nov->ViewValue = $this->obs_nov->CurrentValue;
		$this->obs_nov->ViewCustomAttributes = "";

		// fe_nov
		$this->fe_nov->ViewValue = $this->fe_nov->CurrentValue;
		$this->fe_nov->ViewValue = ew_FormatDateTime($this->fe_nov->ViewValue, 5);
		$this->fe_nov->ViewCustomAttributes = "";

		// estado_nov
		if (strval($this->estado_nov->CurrentValue) <> "") {
			switch ($this->estado_nov->CurrentValue) {
				case $this->estado_nov->FldTagValue(1):
					$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : $this->estado_nov->CurrentValue;
					break;
				case $this->estado_nov->FldTagValue(2):
					$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : $this->estado_nov->CurrentValue;
					break;
				case $this->estado_nov->FldTagValue(3):
					$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : $this->estado_nov->CurrentValue;
					break;
				case $this->estado_nov->FldTagValue(4):
					$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : $this->estado_nov->CurrentValue;
					break;
				case $this->estado_nov->FldTagValue(5):
					$this->estado_nov->ViewValue = $this->estado_nov->FldTagCaption(5) <> "" ? $this->estado_nov->FldTagCaption(5) : $this->estado_nov->CurrentValue;
					break;
				default:
					$this->estado_nov->ViewValue = $this->estado_nov->CurrentValue;
			}
		} else {
			$this->estado_nov->ViewValue = NULL;
		}
		$this->estado_nov->ViewCustomAttributes = "";

		// id_novedad
		$this->id_novedad->LinkCustomAttributes = "";
		$this->id_novedad->HrefValue = "";
		$this->id_novedad->TooltipValue = "";

		// id_afiliado
		$this->id_afiliado->LinkCustomAttributes = "";
		$this->id_afiliado->HrefValue = "";
		$this->id_afiliado->TooltipValue = "";

		// obs_nov
		$this->obs_nov->LinkCustomAttributes = "";
		$this->obs_nov->HrefValue = "";
		$this->obs_nov->TooltipValue = "";

		// fe_nov
		$this->fe_nov->LinkCustomAttributes = "";
		$this->fe_nov->HrefValue = "";
		$this->fe_nov->TooltipValue = "";

		// estado_nov
		$this->estado_nov->LinkCustomAttributes = "";
		$this->estado_nov->HrefValue = "";
		$this->estado_nov->TooltipValue = "";

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
				if ($this->id_novedad->Exportable) $Doc->ExportCaption($this->id_novedad);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->obs_nov->Exportable) $Doc->ExportCaption($this->obs_nov);
				if ($this->fe_nov->Exportable) $Doc->ExportCaption($this->fe_nov);
			} else {
				if ($this->id_novedad->Exportable) $Doc->ExportCaption($this->id_novedad);
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->fe_nov->Exportable) $Doc->ExportCaption($this->fe_nov);
				if ($this->estado_nov->Exportable) $Doc->ExportCaption($this->estado_nov);
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
					if ($this->id_novedad->Exportable) $Doc->ExportField($this->id_novedad);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->obs_nov->Exportable) $Doc->ExportField($this->obs_nov);
					if ($this->fe_nov->Exportable) $Doc->ExportField($this->fe_nov);
				} else {
					if ($this->id_novedad->Exportable) $Doc->ExportField($this->id_novedad);
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->fe_nov->Exportable) $Doc->ExportField($this->fe_nov);
					if ($this->estado_nov->Exportable) $Doc->ExportField($this->estado_nov);
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

		$rsnew['estado_nov'] = '1';
		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE  

		if (CurrentUserLevel() =='3' && $rsold['estado_nov']=='3')  
		{                                            
			$rsnew['estado_nov'] = '4'; 
		 }
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
			if ($this->estado_nov->CurrentValue == 3) {  //si esta pendiente por revisar
				$this->estado_nov->CellAttrs["style"] = "background-color: #ffcccc";
			} elseif ($this->estado_nov->CurrentValue == 6) {
				$this->estado_nov->CellAttrs["style"] = "background-color: #ffcc99";
			} elseif ($this->estado_nov->CurrentValue == 8) {
				$this->estado_nov->CellAttrs["style"] = "background-color: #ffccff";
			}
		}   
	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
