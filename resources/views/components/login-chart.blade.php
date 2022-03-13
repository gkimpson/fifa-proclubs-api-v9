<div
    x-data="{chart: null}"
    x-init="chart = new Chartisan({
        el: '#login-chart',
        url: '@chart('login_chart')',
        hooks: new ChartisanHooks().
        legend(),
      });"
    class="">
    <div x-on:click="chart.update()">
        <i class="fa-solid fa-arrow-rotate-right"></i>
    </div>
    <div id="login-chart" style="height: 300px;"></div>

</div>
@pushOnce('head-scripts')
    <!-- Charting library -->
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
@endPushOnce