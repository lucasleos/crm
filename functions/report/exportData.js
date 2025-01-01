function getFacturacion() {
  return fetchData("../../functions/report/getFacturacion.php");
}

function getCantPptoByMonth() {
  return fetchData("../../functions/report/getCantPresupByMonth.php");
}

function fetchData(url) {
  const selectedYears = getSelectedValues("#anios");
  const selectedMonths = getMesesValues(getSelectedMeses("#meses"));
  const selectedStates = getSelectedStates();

  if (!selectedYears || !selectedMonths || selectedStates.length === 0) {
    return Promise.reject("Años, meses o estados no seleccionados");
  }

  const requestData = {
    anios: selectedYears,
    meses: selectedMonths,
    moneda: getMoneda(),
    estados: selectedStates,
  };

  return ajaxRequest(url, requestData);
}

function ajaxRequest(url, data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url,
      method: "GET",
      data,
      success: (response) =>
        response
          ? resolve(JSON.parse(response))
          : reject("No se obtuvieron datos"),
      error: (err) => reject(err),
    });
  });
}

function exportarToExcel() {
  Promise.all([getFacturacion(), getCantPptoByMonth()])
    .then(([dataFacturacion, dataCantPpto]) => {
      const workbook = createWorkbook();
      const worksheet = workbook.addWorksheet("Facturación");

      const styles = getExcelStyles();

      // Preparar y agregar la tabla de facturación
      const mesesFacturacion = Object.keys(dataFacturacion["Totales"]);
      const facturacionRows = prepareTableData(
        dataFacturacion,
        mesesFacturacion,
        formatNumber
      );
      addExcelTable(
        worksheet,
        "FacturacionTable",
        "A1",
        ["Estado", ...mesesFacturacion, "Total x Estado"],
        facturacionRows,
        styles
      );

      // Preparar y agregar la tabla de cantidad de presupuestos
      const startRowForCantPpto = facturacionRows.length + 5;
      const cantPptoRows = prepareTableData(
        dataCantPpto,
        mesesFacturacion,
        (val) => val
      );
      addExcelTable(
        worksheet,
        "CantPptoTable",
        `A${startRowForCantPpto}`,
        ["Estado", ...mesesFacturacion, "Total x Estado"],
        cantPptoRows,
        styles
      );

      formatWorksheet(worksheet);
      adjustColumnWidths(worksheet);

      exportWorkbookToFile(workbook, "Facturacion.xlsx");
    })
    .catch((error) => console.error("Error al obtener los datos:", error));
}

// Funciones auxiliares para Excel
function createWorkbook() {
  return new ExcelJS.Workbook();
}

function getExcelStyles() {
  return {
    tableStyle: { theme: "TableStyleMedium9", showRowStripes: true },
    headerColor: {
      type: "pattern",
      pattern: "solid",
      fgColor: { argb: "99CCCC" }, // Azul para las cabeceras
    },
    oddRowColor: {
      type: "pattern",
      pattern: "solid",
      fgColor: { argb: "FFEBF1F5" }, // Gris claro para las filas impares
    },
    evenRowColor: {
      type: "pattern",
      pattern: "solid",
      fgColor: { argb: "FFFFFFFF" }, // Blanco para las filas pares
    },
    borderStyle: {
      top: { style: "double", color: { argb: "FFB0B0B0" } }, // Bordes grises
      left: { style: "double", color: { argb: "FFB0B0B0" } },
      bottom: { style: "double", color: { argb: "FFB0B0B0" } },
      right: { style: "double", color: { argb: "FFB0B0B0" } },
    },
    customFont: { name: "Arial", size: 11, color: { argb: "FF000000" } }, // Fuente negra estándar
  };
}

function prepareTableData(data, months, formatFunc) {
  const rows = Object.entries(data)
    .filter(([estado]) => estado !== "Totales")
    .map(([estado, valores]) => {
      let totalEstado = 0;
      const row = [
        estado,
        ...months.map((mes) => {
          const valor = valores[mes] || 0;
          totalEstado += valor;
          return formatFunc(valor);
        }),
      ];
      row.push(formatFunc(totalEstado));
      return row;
    });

  rows.push(prepareTotalRow(data, months, formatFunc));
  return rows;
}

function prepareTotalRow(data, months, formatFunc) {
  const totalRow = ["Totales"];
  let totalFinal = 0;
  months.forEach((mes) => {
    const totalMes = data["Totales"][mes] || 0;
    totalRow.push(formatFunc(totalMes));
    totalFinal += totalMes;
  });
  totalRow.push(formatFunc(totalFinal));
  return totalRow;
}

function addExcelTable(worksheet, tableName, startCell, headers, rows, styles) {
  worksheet.addTable({
    name: tableName,
    ref: startCell,
    headerRow: true,
    style: styles.tableStyle,
    columns: headers.map((header) => ({ name: header })),
    rows,
  });

  applyHeaderStyles(worksheet, startCell, headers.length, styles);
  applyRowStyles(worksheet, startCell, rows.length + 1, styles);
}

function applyHeaderStyles(worksheet, startCell, headerLength, styles) {
  const row = worksheet.getRow(Number(startCell.replace(/[^0-9]/g, "")));
  row.eachCell({ includeEmpty: true }, (cell) => {
    cell.fill = styles.headerColor;
    cell.font = {
      ...styles.customFont,
      bold: true,
      color: { rgba: "000000" },
    };
    cell.border = styles.borderStyle;
  });
}

function applyRowStyles(worksheet, startRow, rowCount, styles) {
  startRow = Number(startRow.replace(/[^0-9]/g, "")) + 1;
  worksheet.eachRow({ includeEmpty: false }, (row, rowNumber) => {
    if (rowNumber >= startRow && rowNumber < startRow + rowCount) {
      row.eachCell({ includeEmpty: true }, (cell) => {
        cell.fill =
          rowNumber % 2 === 0 ? styles.evenRowColor : styles.oddRowColor;
        cell.border = styles.borderStyle;
        cell.font = styles.customFont;
      });
    }
  });
}

function formatWorksheet(worksheet) {
  worksheet.eachRow({ includeEmpty: true }, (row) => {
    row.eachCell((cell) => {
      cell.alignment = { vertical: "middle", horizontal: "center" };
    });
  });
}

function adjustColumnWidths(worksheet) {
  worksheet.columns.forEach((column) => {
    let maxLength = 0;
    column.eachCell({ includeEmpty: true }, (cell) => {
      const columnLength = cell.value ? cell.value.toString().length : 10;
      maxLength = Math.max(maxLength, columnLength);
    });
    column.width = maxLength + 2;
  });
}

function exportWorkbookToFile(workbook, fileName) {
  workbook.xlsx.writeBuffer().then((buffer) => {
    const blob = new Blob([buffer], {
      type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
    });
    saveAs(blob, fileName);
  });
}

function formatNumber(value) {
  return value === 0
    ? "0"
    : `$${value.toLocaleString("en-US", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
      })}`;
}
