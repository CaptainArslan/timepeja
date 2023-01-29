
(chart = new ApexCharts(
    document.querySelector("#apex-line-2"),
    options
)).render();
colors = ["#6658dd", "#f7b84b", "#CED4DC"];
(dataColors = $("#apex-area").data("colors")) &&
    (colors = dataColors.split(","));
options = {
    chart: {
        height: 380,
        type: "area",
        stacked: !0,
        events: {
            selection: function (e, o) {
                console.log(new Date(o.xaxis.min));
            },
        },
    },
    colors: colors,
    dataLabels: { enabled: !1 },
    stroke: { width: [2], curve: "smooth" },
    series: [
        {
            name: "Manager",
            data: generateDayWiseTimeSeries(
                new Date("11 Feb 2017 GMT").getTime(),
                20,
                { min: 10, max: 60 }
            ),
        },
        {
            name: "Driver",
            data: generateDayWiseTimeSeries(
                new Date("11 Feb 2017 GMT").getTime(),
                20,
                { min: 10, max: 20 }
            ),
        },
        {
            name: "Passenger",
            data: generateDayWiseTimeSeries(
                new Date("11 Feb 2017 GMT").getTime(),
                20,
                { min: 10, max: 15 }
            ),
        },
    ],
    fill: { type: "gradient", gradient: { opacityFrom: 0.6, opacityTo: 0.8 } },
    legend: { position: "top", horizontalAlign: "left" },
    xaxis: { type: "datetime" },
};
function generateDayWiseTimeSeries(e, o, t) {
    for (var a = 0, r = []; a < o; ) {
        var s = e,
            i = Math.floor(Math.random() * (t.max - t.min + 1)) + t.min;
        r.push([s, i]), (e += 864e5), a++;
    }
    return r;
}
(chart = new ApexCharts(
    document.querySelector("#apex-area"),
    options
)).render();
colors = ["#6658dd", "#1abc9c", "#CED4DC"];
(dataColors = $("#apex-column-1").data("colors")) &&
    (colors = dataColors.split(","));
options = {
    chart: { height: 380, type: "bar", toolbar: { show: !1 } },
    plotOptions: {
        bar: { horizontal: !1, endingShape: "rounded", columnWidth: "55%" },
    },
    dataLabels: { enabled: !1 },
    stroke: { show: !0, width: 2, colors: ["transparent"] },
    colors: colors,
    series: [
        { name: "Net Profit", data: [44, 55, 57, 56, 61, 58, 63, 60, 66] },
        { name: "Revenue", data: [76, 85, 101, 98, 87, 105, 91, 114, 94] },
        { name: "Free Cash Flow", data: [35, 41, 36, 26, 45, 48, 52, 53, 41] },
    ],
    xaxis: {
        categories: [
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
        ],
    },
    legend: { offsetY: 5 },
    yaxis: { title: { text: "$ (thousands)" } },
    fill: { opacity: 1 },
    grid: {
        row: { colors: ["transparent", "transparent"], opacity: 0.2 },
        borderColor: "#f1f3fa",
        padding: { bottom: 10 },
    },
    tooltip: {
        y: {
            formatter: function (e) {
                return "$ " + e + " thousands";
            },
        },
    },
};