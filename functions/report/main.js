document.addEventListener("DOMContentLoaded", function () {
  getAnios();

  $(".select-all").on("click", function (e) {
    e.preventDefault();
    let targetId = $(this).attr("data-target");
    $("#" + targetId + " .card-select").addClass("selected");
    $(this).hide();
    $(this).siblings(".deselect-all").show();
    setDates(targetId);
  });

  $(".deselect-all").on("click", function (e) {
    e.preventDefault();
    let targetId = $(this).attr("data-target");
    $("#" + targetId + " .card-select").removeClass("selected");
    $(this).hide();
    $(this).siblings(".select-all").show();
    $("#saldototal").html("$0");
    setDates(targetId);
  });

  document.getElementById("anios").addEventListener("click", function (event) {
    if (event.target.classList.contains("card-select")) {
      event.target.classList.toggle("selected");
      getMeses();
    }
  });

  document.getElementById("meses").addEventListener("click", function (event) {
    if (event.target.classList.contains("card-select")) {
      event.target.classList.toggle("selected");
      getTablaFacturacion();
      getCantPresupByStatus();
      getFacturacionByMonth();
    }
  });

  document
    .getElementById("monedas")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("card-select")) {
        event.target.classList.toggle("selected");
        getTablaFacturacion();
        getCantPresupByStatus();
        getFacturacionByMonth();
      }
    });

  document
    .getElementById("estados")
    .addEventListener("click", function (event) {
      if (event.target.classList.contains("card-select")) {
        event.target.classList.toggle("selected");
        getCantPresupByStatus();
        getFacturacionByMonth();
        getTablaFacturacion();
      }
    });
});

function setDates(targetId) {
  if (targetId === "anios") {
    getMeses();
  } else {
    getCantPresupByStatus();
    getFacturacionByMonth();
    getTablaFacturacion();
  }
}

function getAnios() {
  $.ajax({
    url: "../../functions/report/getAnios.php",
    success: function (data) {
      if (data != "") {
        $("#anios").html(data);
      }
    },
  });
}

let mesesGlobal;
function getMeses() {
  const selAnios = Array.from(
    document.querySelectorAll("#anios .card-select.selected"),
    (card) => card.dataset.value
  ).join(",");

  $.ajax({
    url: "../../functions/report/getMeses.php",
    method: "GET",
    data: {
      anios: selAnios,
    },
    success: function (response) {
      const data = JSON.parse(response);
      if (data.html) {
        $("#meses").html(data.html);
      }
      if (data.data) {
        mesesGlobal = data.data;
      }
    },
  });
}

function getMesesValues(mesesString) {
  const mesesArray = mesesString.split(",").map((m) => m.trim().toUpperCase());
  const valoresArray = mesesArray.flatMap((mes) => mesesGlobal[mes] || []);
  valoresArray.sort((a, b) => {
    const [yearA, monthA] = a.split("-").map(Number);
    const [yearB, monthB] = b.split("-").map(Number);
    return yearA === yearB ? monthA - monthB : yearA - yearB;
  });
  return valoresArray.join(",");
}

function getMoneda() {
  const selectedValues = $("#monedas .card-select.selected")
    .map(function () {
      return $(this).data("value");
    })
    .get();
  let result;
  if (selectedValues.includes("USD") && selectedValues.includes("ARS")) {
    result = "ambas";
  } else if (selectedValues.length > 0) {
    result = selectedValues[0];
  } else {
    result = "ambas";
  }
  return result;
}

function getSelectedStates() {
  let selectedStates = [];

  $("#estados .card-select.selected").each(function () {
    selectedStates.push($(this).data("value"));
  });

  return selectedStates;
}

function getCantPresupByStatus() {
  const selAnios = getSelectedValues("#anios");
  const selNomMen = getSelectedMeses("#meses");
  const valoresMeses = getMesesValues(selNomMen);
  const selMeses = valoresMeses;

  if (!selAnios || !selMeses || getSelectedStates().length === 0) {
    toggleDisplay("grafico-donut-1", false);
    toggleDisplay("seleccionarDatos1", true);
    return;
  }

  $.ajax({
    url: "../../functions/report/getCantPresupByStatus.php",
    method: "GET",
    data: {
      anios: selAnios,
      meses: selMeses,
      moneda: getMoneda(),
      estados: getSelectedStates(),
    },
    success: function (data) {
      if (data && data.length > 0) {
        toggleDisplay("seleccionarDatos1", false);
        toggleDisplay("grafico-donut-1", true);
        const parsedData = JSON.parse(data);
        setFacturacionTotal(selAnios, selMeses, parsedData);
        renderChart(parsedData);
      } else {
        alert("No se encontraron datos para los meses y años seleccionados.");
      }
    },
    error: function () {
      alert("Error al obtener los datos del servidor.");
    },
  });
}

function setFacturacionTotal(selAnios, selMeses, statusData) {
  let simbolo = "AR$";
  $.ajax({
    url: "../../functions/report/getFacturacionTotal.php",
    method: "GET",
    data: {
      anios: selAnios,
      meses: selMeses,
      moneda: getMoneda(),
      estados: getSelectedStates(),
    },
    success: function (data) {
      if (data && !isNaN(data)) {
        if (getMoneda() === "USD") {
          simbolo = "U$D";
        }
        const formattedData = Number(data).toLocaleString();
        $("#saldototal").html(
          `${simbolo} ${formattedData}<br>${getCantPresupuestos(
            statusData
          )} presupuestos`
        );
      } else {
        alert(
          "No se encontraron datos monetarios para los meses y años seleccionados."
        );
      }
    },
    error: function () {
      alert("Error al obtener los datos monetarios del servidor.");
    },
  });
}

function getCantPresupuestos(data) {
  return data.reduce((sum, item) => sum + item.value, 0);
}

let FacturacionByMonth;
function getFacturacionByMonth() {
  const selAnios = getSelectedValues("#anios");
  const selNomMen = getSelectedMeses("#meses");
  const valoresMeses = getMesesValues(selNomMen);
  const selMeses = valoresMeses;

  if (!selAnios || !selMeses || getSelectedStates().length === 0) {
    toggleDisplay("grafico-funnel-1", false);
    toggleDisplay("seleccionarDatos2", true);
    return;
  }

  $.ajax({
    url: "../../functions/report/getFacturacionByMonth.php",
    data: {
      anios: selAnios,
      meses: selMeses,
      moneda: getMoneda(),
      estados: getSelectedStates(),
    },
    success: function (data) {
      if (data) {
        FacturacionByMonth = toArray(JSON.parse(data));
        toggleDisplay("seleccionarDatos2", false);
        toggleDisplay("grafico-funnel-1", true);
        renderBarChart(JSON.parse(data));
      } else {
        alert("No se encontraron datos para los meses y años seleccionados.");
      }
    },
  });
}

function toArray(data) {
  const result = [];

  for (const estado in data) {
    for (const mes in data[estado]) {
      result.push({
        estado: estado,
        mes: parseInt(mes),
        cantidad: data[estado][mes],
      });
    }
  }
  return result;
}

function getSelectedValues(selector) {
  return Array.from(
    document.querySelectorAll(`${selector} .card-select.selected`),
    (card) => card.dataset.value
  ).join(",");
}

function getSelectedMeses(selector) {
  return Array.from(
    document.querySelectorAll(`${selector} .card-select.selected`)
  )
    .map((card) => card.textContent)
    .join(",");
}

function toggleDisplay(elementId, shouldShow) {
  document.getElementById(elementId).style.display = shouldShow
    ? "block"
    : "none";
}

function getTablaFacturacion() {
  const selAnios = getSelectedValues("#anios");
  const selNomMen = getSelectedMeses("#meses");
  const valoresMeses = getMesesValues(selNomMen);
  const selMeses = valoresMeses;

  if (!selAnios || !selMeses || getSelectedStates().length === 0) {
    toggleDisplay("tableTitle", false);
    toggleDisplay("seleccionarDatos0", true);
    toggleDisplay("tablafacturacion", false);
    return [];
  }

  $.ajax({
    url: "../../functions/report/getTablaFacturacion.php",
    method: "GET",
    data: {
      anios: selAnios,
      meses: selMeses,
      moneda: getMoneda(),
      estados: getSelectedStates(),
    },
    success: function (data) {
      toggleDisplay("tableTitle", true);
      toggleDisplay("seleccionarDatos0", false);
      toggleDisplay("tablafacturacion", true);
      $("#tablafacturacion").html(data);

      if (data != "") {
        $("#tablafacturacion").html(data);
        $('[data-toggle="tooltip"]').tooltip();
      }
    },
  });
}

// Gráficos

function renderChart(data) {
  let chart = echarts.init(document.getElementById("grafico-donut-1"));
  chart.clear();

  let option = {
    title: {
      text: "Estado de los Presupuestos",
      left: "center",
      textStyle: {
        color: "#fff",
        fontSize: 18,
        fontWeight: "bold",
      },
    },
    tooltip: {
      trigger: "item",
    },
    legend: {
      top: "bottom",
      textStyle: {
        color: "#fff",
        fontWeight: "bold",
      },
    },
    series: [
      {
        type: "pie",
        radius: ["50%", "70%"],
        avoidLabelOverlap: false,
        itemStyle: {
          borderRadius: 10,
          borderColor: "#fff",
          borderWidth: 2,
        },
        label: {
          show: true,
          position: "inside",
          formatter: "{c}",
          color: "#fff",
          fontSize: 14,
          fontWeight: "bold",
        },
        emphasis: {
          label: {
            show: true,
            fontSize: "20",
            fontWeight: "bold",
          },
        },
        labelLine: {
          show: false,
        },
        data: data.map((item) => ({
          ...item,
          itemStyle: {
            color: colorMap[item.name] || "#ccc",
          },
        })),
      },
    ],
  };

  chart.setOption(option);
}

function renderBarChart(data) {
  let chart = echarts.init(document.getElementById("grafico-funnel-1"));
  chart.clear();

  let seriesData = [];
  let filteredCategories = [];
  let estados = Object.keys(data);

  let tempData = {};

  estados.forEach((estado) => {
    tempData[estado] = [];
    for (let mes in data[estado]) {
      let value = data[estado][mes] || 0;
      tempData[estado][mes] = value;

      if (!filteredCategories.includes(mes)) {
        filteredCategories.push(mes);
      }
    }
  });

  filteredCategories.sort();

  estados.forEach((estado) => {
    let estadoData = [];
    filteredCategories.forEach((mes) => {
      estadoData.push(tempData[estado][mes] || 0);
    });

    seriesData.push({
      name: estado,
      type: "bar",
      emphasis: {
        focus: "series",
      },
      itemStyle: {
        color: colorMap[estado],
      },
      data: estadoData,
      label: {
        show: true,
        position: "top",
        rotate: 50,
        formatter: function (params) {
          let simbolo = getMoneda() === "USD" ? "U$D" : "$";
          return params.value > 0
            ? simbolo + params.value.toLocaleString()
            : "";
        },
        color: "#fff",
      },
      labelLayout: {
        hideOverlap: true,
      },
    });
  });

  let option = {
    title: {
      text: "Evolución de los Montos según Estado del Presupuesto",
      left: "center",
      textStyle: {
        color: "#fff",
        fontSize: 18,
        fontWeight: "bold",
      },
    },
    tooltip: {
      trigger: "axis",
      axisPointer: {
        type: "shadow",
      },
      formatter: function (params) {
        let result = params[0].name + "<br/>";
        params.forEach(function (item) {
          result +=
            item.marker +
            " " +
            item.seriesName +
            ": $" +
            item.value.toLocaleString() +
            "<br/>";
        });
        return result;
      },
    },
    legend: {
      top: "bottom",
      textStyle: {
        color: "#fff",
        fontWeight: "bold",
      },
    },
    grid: {
      left: "2%",
      right: "2%",
      bottom: "10%",
      containLabel: true,
    },
    xAxis: {
      type: "category",
      data: filteredCategories,
      axisLabel: {
        color: "#fff",
      },
    },
    yAxis: {
      type: "value",
      axisLabel: {
        show: false,
      },
      axisTick: {
        show: false,
      },
      axisLine: {
        show: false,
      },
    },
    series: seriesData,
  };

  chart.setOption(option);
}

const colorMap = {
  "En proceso de cotización": "#1a1aff",
  "Esperando respuesta": "#ffc107",
  "Pendiente de análisis": "#17a2b8",
  "Cerrado - GANADO": "#28a745",
  "Cerrado - NO COMERCIALIZAMOS": "#ff5000",
  "Cerrado - PERDIDO": "#dc3545",
  "Cerrado - VENCIDO": "#6c757d",
};

const divs = ["graphUno", "graphDos", "graphTabla"];

function maxDiv(div) {
  document.getElementById("allFixed").style.display = "none";

  const allStretch = document.getElementById("allStretch");
  allStretch.style.width = "100%";
  allStretch.style.marginLeft = "0";
  allStretch.style.maxWidth = "100%";
  allStretch.style.flex = "1 1 0%";

  divs.forEach((divv) => {
    document.getElementById(divv).style.display =
      div === divv ? "block" : "none";
  });

  document
    .querySelectorAll(".allfs")
    .forEach((a) => (a.style.display = "none"));

  document
    .querySelectorAll(".allfsn")
    .forEach((an) => (an.style.display = "block"));

  if (div === "graphDos") {
    resizeChart("grafico-funnel-1");
  } else {
    resizeChart("grafico-donut-1");
  }
}

function minDiv(div) {
  document.getElementById("allFixed").style.display = "block";

  const allStretch = document.getElementById("allStretch");
  allStretch.style.width = "";
  allStretch.style.marginLeft = "";
  allStretch.style.maxWidth = "";
  allStretch.style.flex = "";

  divs.forEach((divv) => {
    document.getElementById(divv).style.display = "block";
  });

  document
    .querySelectorAll(".allfs")
    .forEach((a) => (a.style.display = "block"));

  document
    .querySelectorAll(".allfsn")
    .forEach((an) => (an.style.display = "none"));

  if (div === "graphDos") {
    resizeChart("grafico-funnel-1");
  } else {
    resizeChart("grafico-donut-1");
  }
}

function resizeChart(graphId) {
  let chart = echarts.getInstanceByDom(document.getElementById(graphId));
  chart.resize();
}
