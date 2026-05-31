export function initChartSix(data = []) {
    const chartSixEl = document.querySelector("#chartSix");
    if (chartSixEl) {
        if (chartSixEl.__chartSix) {
            return chartSixEl.__chartSix;
        }

        const source = Array.isArray(data) && data.length ? data : [
            { gadai: 44, perpanjangan: 13, pelunasan: 11, jatuh_tempo: 21 },
            { gadai: 55, perpanjangan: 23, pelunasan: 17, jatuh_tempo: 7 },
            { gadai: 41, perpanjangan: 20, pelunasan: 15, jatuh_tempo: 25 },
            { gadai: 67, perpanjangan: 8, pelunasan: 15, jatuh_tempo: 13 },
            { gadai: 22, perpanjangan: 13, pelunasan: 21, jatuh_tempo: 22 },
            { gadai: 43, perpanjangan: 27, pelunasan: 14, jatuh_tempo: 8 },
            { gadai: 55, perpanjangan: 13, pelunasan: 18, jatuh_tempo: 18 },
            { gadai: 41, perpanjangan: 23, pelunasan: 20, jatuh_tempo: 20 },
        ];
        const chartSixOptions = {
            series: [
                {
                    name: "Gadai",
                    data: source.map((item) => item.gadai || 0),
                },
                {
                    name: "Perpanjangan",
                    data: source.map((item) => item.perpanjangan || 0),
                },
                {
                    name: "Pelunasan",
                    data: source.map((item) => item.pelunasan || 0),
                },
                {
                    name: "Jatuh Tempo",
                    data: source.map((item) => item.jatuh_tempo || 0),
                },
            ],
            colors: ["#174a2e", "#1F5C3A", "#4a8c5c", "#d4edaa"],
            chart: {
                fontFamily: "Outfit, sans-serif",
                type: "bar",
                stacked: true,
                height: 315,
                toolbar: {
                    show: false,
                },
                zoom: {
                    enabled: false,
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "39%",
                    borderRadius: 10,
                    borderRadiusApplication: "end",
                    borderRadiusWhenStacked: "last",
                },
            },
            dataLabels: {
                enabled: false,
            },
            xaxis: {
                categories: [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Okt",
                    "Nov",
                    "Des",
                ],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false,
                },
            },
            legend: {
                show: true,
                position: "top",
                horizontalAlign: "left",
                fontFamily: "Outfit",
                fontSize: "14px",
                fontWeight: 400,
                markers: {
                    size: 5,
                    shape: "circle",
                    radius: 999,
                    strokeWidth: 0,
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0,
                },
            },
            yaxis: {
                title: false,
            },
            grid: {
                yaxis: {
                    lines: {
                        show: true,
                    },
                },
            },
            fill: {
                opacity: 1,
            },

            tooltip: {
                x: {
                    show: false,
                },
                y: {
                    formatter: function (val) {
                        return val;
                    },
                },
            },
        };

        const chartSix = new ApexCharts(chartSixEl, chartSixOptions);
        chartSix.render();
        chartSixEl.__chartSix = chartSix;
        return chartSix;
    }
}
