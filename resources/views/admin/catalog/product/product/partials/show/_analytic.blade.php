<!--begin::Card header-->
<div class="card-header cursor-pointer">
    <!--begin::Card title-->
    <div class="card-title m-0">
        <h3 class="fw-bolder m-0">{{ __('Statistical data') }}</h3>
    </div>
    <!--end::Card title-->
</div>
<!--begin::Card header-->
<!--begin::Card body-->
<div x-data="productViewsChart" class="card-body p-9">
    <div wire:ignore id="product_views_chart" style="height: 300px;"></div>
</div>

@script
    <script>
        Alpine.data('productViewsChart', () => ({
            chart: null,
            graphData: @json($graphicViewsData),

            init() {
                this.renderChart();
            },
            renderChart() {
                const element = this.$el.querySelector('#product_views_chart');
                if (!element) {
                    return;
                }

                const dates = Array.isArray(this.graphData.dates) ? this.graphData.dates : [];
                const totals = Array.isArray(this.graphData.totals) ? this.graphData.totals : [];

                if (!dates.length || !totals.length) {
                    element.innerHTML = '<div class="text-center text-muted">No hay datos para mostrar</div>';
                    return;
                }

                const height = parseInt(KTUtil.css(element, 'height'), 10) || 300;
                const labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
                const borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');
                const successColor = KTUtil.getCssVariableValue('--bs-success');

                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }

                this.chart = new ApexCharts(element, {
                    series: [{ name: 'Views', data: totals }],
                    chart: {
                        type: 'area',
                        height: height,
                        toolbar: { show: false },
                        fontFamily: 'inherit'
                    },
                    legend: { show: false },
                    dataLabels: { enabled: false },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        show: true,
                        width: 3,
                        colors: [successColor]
                    },
                    xaxis: {
                        categories: dates,
                        axisBorder: { show: false },
                        axisTicks: { show: false },
                        labels: {
                            rotate: -45,
                            rotateAlways: true,
                            style: { colors: [labelColor], fontSize: '12px' }
                        }
                    },
                    yaxis: {
                        tickAmount: 4,
                        min: 0,
                        labels: { style: { colors: [labelColor], fontSize: '12px' } }
                    },
                    colors: [successColor],
                    grid: {
                        borderColor: borderColor,
                        strokeDashArray: 4,
                        yaxis: { lines: { show: true } }
                    },
                    tooltip: {
                        style: { fontSize: '12px' },
                        y: { formatter: function (val) { return val; } }
                    },
                    markers: { strokeColor: successColor, strokeWidth: 3 }
                });

                this.chart.render();
            }
        }));
    </script>
@endscript
