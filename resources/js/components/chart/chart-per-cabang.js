import ApexCharts from 'apexcharts';

export function initChartPerCabang(data = []) {
    const chartEl = document.querySelector('#chartPie');
    if (!chartEl) return null;

    if (chartEl.__chartPerCabang) {
        return chartEl.__chartPerCabang;
    }

    const perCabang = Array.isArray(data) ? data : [];
    const hasData = perCabang.some((item) => (item.total || 0) > 0);

    const chart = new ApexCharts(chartEl, {
        series: hasData ? perCabang.map((item) => item.total || 0) : [1],
        chart: {
            type: 'donut',
            height: 250,
            fontFamily: 'Outfit, sans-serif',
            toolbar: { show: false },
        },
        labels: hasData ? perCabang.map((item) => item.nama) : ['Belum ada data'],
        colors: hasData ? ['#1F5C3A', '#B6D96C', '#174a2e', '#4CAF50', '#8BC34A'] : ['#E5E7EB'],
        legend: { show: false },
        dataLabels: { enabled: false },
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        name: {
                            show: true,
                            fontSize: '13px',
                            fontFamily: 'Outfit, sans-serif',
                            fontWeight: 400,
                            color: '#6B7280',
                            offsetY: -4,
                        },
                        value: {
                            show: true,
                            fontSize: '22px',
                            fontFamily: 'Outfit, sans-serif',
                            fontWeight: 700,
                            color: '#111827',
                            offsetY: 4,
                            formatter: (val) => hasData ? val : '0',
                        },
                        total: {
                            show: true,
                            label: 'Total',
                            fontSize: '13px',
                            fontFamily: 'Outfit, sans-serif',
                            fontWeight: 400,
                            color: '#6B7280',
                            formatter: (w) => hasData
                                ? w.globals.seriesTotals.reduce((total, value) => total + value, 0)
                                : 0,
                        },
                    },
                },
            },
        },
        stroke: { width: 2, colors: ['#ffffff'] },
        tooltip: { enabled: false },
    });

    chart.render();
    chartEl.__chartPerCabang = chart;

    return chart;
}
