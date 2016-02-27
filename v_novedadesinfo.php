<?php

// Global variable for table object
$v_novedades = NULL;

//
// Table class for v_novedades
//
class cv_novedades extends cTable {
	var $id_afiliado;
	var $apell_afiliado;
	var $dociden_afiliado;
	var $nomb_afiliado;
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
		$this->TableVar = 'v_novedades';
		$this->TableName = 'v_novedades';
		$this->TableType = 'VIEW';
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

		// id_afiliado
		$this->id_afiliado = new cField('v_novedades', 'v_novedades', 'x_id_afiliado', 'id_afiliado', '`id_afiliado`', '`id_afiliado`', 3, -1, FALSE, '`id_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_afiliado->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_afiliado'] = &$this->id_afiliado;

		// apell_afiliado
		$this->apell_afiliado = new cField('v_novedades', 'v_novedades', 'x_apell_afiliado', 'apell_afiliado', '`apell_afiliado`', '`apell_afiliado`', 200, -1, FALSE, '`apell_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['apell_afiliado'] = &$this->apell_afiliado;

		// dociden_afiliado
		$this->dociden_afiliado = new cField('v_novedades', 'v_novedades', 'x_dociden_afiliado', 'dociden_afiliado', '`dociden_afiliado`', '`dociden_afiliado`', 200, -1, FALSE, '`dociden_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dociden_afiliado'] = &$this->dociden_afiliado;

		// nomb_afiliado
		$this->nomb_afiliado = new cField('v_novedades', 'v_novedades', 'x_nomb_afiliado', 'nomb_afiliado', '`nomb_afiliado`', '`nomb_afiliado`', 200, -1, FALSE, '`nomb_afiliado`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['nomb_afiliado'] = &$this->nomb_afiliado;

		// obs_nov
		$this->obs_nov = new cField('v_novedades', 'v_novedades', 'x_obs_nov', 'obs_nov', '`obs_nov`', '`obs_nov`', 201, -1, FALSE, '`obs_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['obs_nov'] = &$this->obs_nov;

		// fe_nov
		$this->fe_nov = new cField('v_novedades', 'v_novedades', 'x_fe_nov', 'fe_nov', '`fe_nov`', 'DATE_FORMAT(`fe_nov`, \'%Y/%m/%d\')', 135, 5, FALSE, '`fe_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fe_nov->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['fe_nov'] = &$this->fe_nov;

		// estado_nov
		$this->estado_nov = new cField('v_novedades', 'v_novedades', 'x_estado_nov', 'estado_nov', '`estado_nov`', '`estado_nov`', 16, -1, FALSE, '`estado_nov`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
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

	// Table level SQL
	function SqlFrom() { // From
		return "`v_novedades`";
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
				return TRUE;
			default:
				return TRUE;
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
	var $UpdateTable = "`v_novedades`";

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
		return "";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
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
			return "v_novedadeslist.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "v_novedadeslist.php";
	}

	// View URL
	function GetViewUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl("v_novedadesview.php", $this->UrlParm($parm));
		else
			return $this->KeyUrl("v_novedadesview.php", $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Add URL
	function GetAddUrl() {
		return "v_novedadesadd.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("v_novedadesedit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("v_novedadesadd.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("v_novedadesdelete.php", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
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

			//return $arKeys; // Do not return yet, so the values will also be checked by the following code
		}

		// Check keys
		$ar = array();
		foreach ($arKeys as $key) {
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
		$this->id_afiliado->setDbValue($rs->fields('id_afiliado'));
		$this->apell_afiliado->setDbValue($rs->fields('apell_afiliado'));
		$this->dociden_afiliado->setDbValue($rs->fields('dociden_afiliado'));
		$this->nomb_afiliado->setDbValue($rs->fields('nomb_afiliado'));
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
		// id_afiliado
		// apell_afiliado
		// dociden_afiliado
		// nomb_afiliado
		// obs_nov
		// fe_nov
		// estado_nov
		// id_afiliado

		$this->id_afiliado->ViewValue = $this->id_afiliado->CurrentValue;
		$this->id_afiliado->ViewCustomAttributes = "";

		// apell_afiliado
		$this->apell_afiliado->ViewValue = $this->apell_afiliado->CurrentValue;
		$this->apell_afiliado->ViewCustomAttributes = "";

		// dociden_afiliado
		$this->dociden_afiliado->ViewValue = $this->dociden_afiliado->CurrentValue;
		$this->dociden_afiliado->ViewCustomAttributes = "";

		// nomb_afiliado
		$this->nomb_afiliado->ViewValue = $this->nomb_afiliado->CurrentValue;
		$this->nomb_afiliado->ViewCustomAttributes = "";

		// obs_nov
		$this->obs_nov->ViewValue = $this->obs_nov->CurrentValue;
		$this->obs_nov->ViewCustomAttributes = "";

		// fe_nov
		$this->fe_nov->ViewValue = $this->fe_nov->CurrentValue;
		$this->fe_nov->ViewValue = ew_FormatDateTime($this->fe_nov->ViewValue, 5);
		$this->fe_nov->ViewCustomAttributes = "";

		// estado_nov
		if (strval($this->estado_nov->CurrentValue) <> "") {
			$this->estado_nov->ViewValue = "";
			$arwrk = explode(",", strval($this->estado_nov->CurrentValue));
			$cnt = count($arwrk);
			for ($ari = 0; $ari < $cnt; $ari++) {
				switch (trim($arwrk[$ari])) {
					case $this->estado_nov->FldTagValue(1):
						$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(1) <> "" ? $this->estado_nov->FldTagCaption(1) : trim($arwrk[$ari]);
						break;
					case $this->estado_nov->FldTagValue(2):
						$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(2) <> "" ? $this->estado_nov->FldTagCaption(2) : trim($arwrk[$ari]);
						break;
					case $this->estado_nov->FldTagValue(3):
						$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(3) <> "" ? $this->estado_nov->FldTagCaption(3) : trim($arwrk[$ari]);
						break;
					case $this->estado_nov->FldTagValue(4):
						$this->estado_nov->ViewValue .= $this->estado_nov->FldTagCaption(4) <> "" ? $this->estado_nov->FldTagCaption(4) : trim($arwrk[$ari]);
						break;
					default:
						$this->estado_nov->ViewValue .= trim($arwrk[$ari]);
				}
				if ($ari < $cnt-1) $this->estado_nov->ViewValue .= ew_ViewOptionSeparator($ari);
			}
		} else {
			$this->estado_nov->ViewValue = NULL;
		}
		$this->estado_nov->ViewCustomAttributes = "";

		// id_afiliado
		$this->id_afiliado->LinkCustomAttributes = "";
		$this->id_afiliado->HrefValue = "";
		$this->id_afiliado->TooltipValue = "";

		// apell_afiliado
		$this->apell_afiliado->LinkCustomAttributes = "";
		$this->apell_afiliado->HrefValue = "";
		$this->apell_afiliado->TooltipValue = "";

		// dociden_afiliado
		$this->dociden_afiliado->LinkCustomAttributes = "";
		$this->dociden_afiliado->HrefValue = "";
		$this->dociden_afiliado->TooltipValue = "";

		// nomb_afiliado
		$this->nomb_afiliado->LinkCustomAttributes = "";
		$this->nomb_afiliado->HrefValue = "";
		$this->nomb_afiliado->TooltipValue = "";

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
			} else {
				if ($this->id_afiliado->Exportable) $Doc->ExportCaption($this->id_afiliado);
				if ($this->apell_afiliado->Exportable) $Doc->ExportCaption($this->apell_afiliado);
				if ($this->dociden_afiliado->Exportable) $Doc->ExportCaption($this->dociden_afiliado);
				if ($this->nomb_afiliado->Exportable) $Doc->ExportCaption($this->nomb_afiliado);
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
				} else {
					if ($this->id_afiliado->Exportable) $Doc->ExportField($this->id_afiliado);
					if ($this->apell_afiliado->Exportable) $Doc->ExportField($this->apell_afiliado);
					if ($this->dociden_afiliado->Exportable) $Doc->ExportField($this->dociden_afiliado);
					if ($this->nomb_afiliado->Exportable) $Doc->ExportField($this->nomb_afiliado);
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

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
