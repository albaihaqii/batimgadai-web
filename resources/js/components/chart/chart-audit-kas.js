import ApexCharts from 'apexcharts';

export function initChartAuditKas(data = []) {
    const chartEl = document.querySelector('#chartAuditKas');
    if (!chartEl) return null;

    if (chartEl.__chartAuditKas) {
        return chartEl.__chartAuditKas;
    }

    const auditKas = Array.isArray(data) ? data : [];

    const chart = new ApexCharts(chartEl, {
        series: [
            { name: 'Uang Keluar', data: auditKas.map((item) => item.keluar || 0) },
            { name: 'Uang Didapatkan', data: auditKas.map((item) => item.masuk || 0) },
        ],
        chart: {
            type: 'area',
            stacked: true,
            height: 340,
            fontFamily: 'Outfit, sans-serif',
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        colors: ['#DC2626', '#16A34A'],
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        fill: {
            type: 'gradient',
            gradient: { opacityFrom: 0.42, opacityTo: 0.08 },
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
            axisBorder: { show: false },
            axisTicks: { show: false },
        },
        yaxis: {
            labels: {
                formatter: (val) => val >= 1000000
                    ? 'Rp ' + (val / 1000000).toFixed(0) + ' Jt'
                    : 'Rp ' + Number(val).toLocaleString('id-ID'),
            },
        },
        legend: {
            position: 'top',
            horizontalAlign: 'left',
            fontFamily: 'Outfit',
            markers: { size: 5, shape: 'circle' },
        },
        grid: { borderColor: '#E5E7EB', strokeDashArray: 4 },
        tooltip: {
            y: { formatter: (val) => 'Rp ' + Number(val).toLocaleString('id-ID') },
        },
    });

    chart.render();
    chartEl.__chartAuditKas = chart;

    return chart;
}
