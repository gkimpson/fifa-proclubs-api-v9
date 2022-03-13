<div
    x-data="{chart: null}"
    x-init="chart = new Chartisan({
        el: '#goal-chart',
        url: '@chart('goal_chart')',
        hooks: new ChartisanHooks()
        .tooltip()
        .legend()
      });"
    class="">
    <div x-on:click="chart.update()">
        <i class="fa-solid fa-arrow-rotate-right"></i>
    </div>
    <div id="goal-chart" style="height: 300px;"></div>

</div>
@pushOnce('head-scripts')
    <!-- Charting library -->
    <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
    <!-- Chartisan -->
    <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
@endPushOnce