'use strict';

document.addEventListener('DOMContentLoaded', function () {
    const allData = {
        temperature: temperatureData,
        humidity: humidityData,
        activity: activityData,
        tvoc: tvocData,
        illumination: illuminationData,
        infrared: infraredData,
        infraredVisible: infraredVisibleData,
        pressure: pressureData,
        // Convert timestamps to Date objects
        timestamps: timestamps.map((timestamp) => new Date(timestamp))
    };

    // We will store the final filtered & grouped data here:
    let filteredData = { ...allData };

    // Keep track of the current period ("Today" or "Weekly")
    let currentPeriod = 'Today';

    // ─────────────────────────────────────────────────────────────────────────────
    // GROUPING FUNCTION
    // ─────────────────────────────────────────────────────────────────────────────
    function groupAndAverage(period) {
        // We want to group ONLY the already-filtered data in 'filteredData'
        const groupedData = {
            temperature: {},
            humidity: {},
            activity: {},
            tvoc: {},
            illumination: {},
            infrared: {},
            infraredVisible: {},
            pressure: {}
        };
        const groupedTimestamps = {};

        // Decide the grouping key: by hour if Today, else by day
        const formatKey = (date) => {
            if (period === 'Today') {
                // e.g. "13:00"
                return `${date.getHours()}:00`;
            } else {
                // e.g. "2025-01-14"
                return date.toISOString().split('T')[0];
            }
        };

        // Loop through the already filtered timestamps
        filteredData.timestamps.forEach((date, index) => {
            const key = formatKey(date);

            if (!groupedData.temperature[key]) {
                groupedData.temperature[key] = [];
                groupedData.humidity[key] = [];
                groupedData.activity[key] = [];
                groupedData.tvoc[key] = [];
                groupedData.illumination[key] = [];
                groupedData.infrared[key] = [];
                groupedData.infraredVisible[key] = [];
                groupedData.pressure[key] = [];
                // We’ll store just one representative Date for each group
                groupedTimestamps[key] = date;
            }

            groupedData.temperature[key].push(filteredData.temperature[index]);
            groupedData.humidity[key].push(filteredData.humidity[index]);
            groupedData.activity[key].push(filteredData.activity[index]);
            groupedData.tvoc[key].push(filteredData.tvoc[index]);
            groupedData.illumination[key].push(filteredData.illumination[index]);
            groupedData.infrared[key].push(filteredData.infrared[index]);
            groupedData.infraredVisible[key].push(filteredData.infraredVisible[index]);
            groupedData.pressure[key].push(filteredData.pressure[index]);
        });

        // Calculate average for each group
        const calculateAverage = (values) => {
            return values.reduce((sum, v) => sum + v, 0) / values.length;
        };

        // Build new structure with the average values
        const averagedData = {
            temperature: [],
            humidity: [],
            activity: [],
            tvoc: [],
            illumination: [],
            infrared: [],
            infraredVisible: [],
            pressure: [],
            timestamps: []
        };

        Object.keys(groupedData.temperature).forEach((key) => {
            averagedData.temperature.push(calculateAverage(groupedData.temperature[key]));
            averagedData.humidity.push(calculateAverage(groupedData.humidity[key]));
            averagedData.activity.push(calculateAverage(groupedData.activity[key]));
            averagedData.tvoc.push(calculateAverage(groupedData.tvoc[key]));
            averagedData.illumination.push(calculateAverage(groupedData.illumination[key]));
            averagedData.infrared.push(calculateAverage(groupedData.infrared[key]));
            averagedData.infraredVisible.push(calculateAverage(groupedData.infraredVisible[key]));
            averagedData.pressure.push(calculateAverage(groupedData.pressure[key]));
            averagedData.timestamps.push(groupedTimestamps[key]);
        });

        return averagedData;
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // FILTER FUNCTION
    // ─────────────────────────────────────────────────────────────────────────────
    function filterData(period) {
        currentPeriod = period; // store selected period
        const now = new Date();

        // We'll do "todayString" to avoid subtle time-zone issues
        const todayString = new Date().toDateString();
        let cutoff;

        // Decide cutoff for weekly
        if (period === 'Weekly') {
            cutoff = new Date(now.setDate(now.getDate() - 7));
        }

        // Filter to keep only the relevant indices
        const indices = allData.timestamps
            .map((date, index) => {
                if (period === 'Today') {
                    // Keep if date is exactly "today" in local calendar
                    return (date.toDateString() === todayString) ? index : -1;
                } else if (period === 'Weekly') {
                    // Keep if within last 7 days
                    return (date >= cutoff) ? index : -1;
                }
                // In case of other unhandled period, discard
                return -1;
            })
            .filter((i) => i !== -1);

        // Build a filtered dataset
        filteredData = {
            temperature: indices.map((i) => allData.temperature[i]),
            humidity: indices.map((i) => allData.humidity[i]),
            activity: indices.map((i) => allData.activity[i]),
            tvoc: indices.map((i) => allData.tvoc[i]),
            illumination: indices.map((i) => allData.illumination[i]),
            infrared: indices.map((i) => allData.infrared[i]),
            infraredVisible: indices.map((i) => allData.infraredVisible[i]),
            pressure: indices.map((i) => allData.pressure[i]),
            timestamps: indices.map((i) => allData.timestamps[i])
        };

        // Now group & average that filtered dataset
        filteredData = groupAndAverage(period);
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // RENDER CHART
    // ─────────────────────────────────────────────────────────────────────────────
    function renderChart() {
        // Decide how to format timestamps on the X-axis
        let xFormatOptions;
        if (currentPeriod === 'Weekly') {
            // Show only day/month/year
            xFormatOptions = {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit'
            };
        } else {
            // "Today": show date + hour/minute
            xFormatOptions = {
                day: '2-digit',
                month: '2-digit',
                year: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            };
        }

        const options = {
            chart: {
                type: 'line',
                height: 350,
                toolbar: {
                    show: true
                }
            },
            colors: ['#e58a00', '#4680ff', '#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#20c997'],
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true,
                position: 'bottom'
            },
            markers: {
                size: 3,
                hover: {
                    size: 5
                }
            },
            stroke: {
                width: 2,
                curve: 'smooth'
            },
            series: [
                { name: 'Temperature', data: filteredData.temperature },
                { name: 'Humidity', data: filteredData.humidity },
                { name: 'Activity', data: filteredData.activity },
                { name: 'TVOC', data: filteredData.tvoc },
                { name: 'Illumination', data: filteredData.illumination },
                { name: 'Infrared', data: filteredData.infrared },
                { name: 'Infrared and Visible', data: filteredData.infraredVisible },
                { name: 'Pressure', data: filteredData.pressure }
            ],
            xaxis: {
                categories: filteredData.timestamps.map((date) =>
                    date.toLocaleString('en-GB', xFormatOptions)
                ),
                title: {
                    text: 'Date et Heure'
                }
            },
            yaxis: {
                title: {
                    text: 'Valeurs'
                },
                labels: {
                    formatter: function (value) {
                        return Math.ceil(value); // Round up to the nearest integer
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false
            }
        };

        const chartElement = document.querySelector('#revenue-sales-chart');
        chartElement.innerHTML = ''; // Clear existing chart
        const chart = new ApexCharts(chartElement, options);
        chart.render();
    }

    // ─────────────────────────────────────────────────────────────────────────────
    // INITIALIZE
    // ─────────────────────────────────────────────────────────────────────────────
    // Default to "Today"
    filterData('Today');
    renderChart();

    // ─────────────────────────────────────────────────────────────────────────────
    // DROPDOWN LISTENER
    // ─────────────────────────────────────────────────────────────────────────────
    document.querySelector('.form-select').addEventListener('change', function (event) {
        const selectedPeriod = event.target.value; // 'Today' or 'Weekly'
        filterData(selectedPeriod);
        renderChart();
    });

    // ─────────────────────────────────────────────────────────────────────────────
    // OPTIONAL: REMOVE "MONTHLY" AND AUTO-SELECT WEEKLY
    // ─────────────────────────────────────────────────────────────────────────────
    setTimeout(() => {
        // Target the select dropdown
        const dropdown = document.querySelector('.form-select');

        // Find and remove the "Monthly" option
        const monthlyOption = Array.from(dropdown.options).find(option => option.text === "Monthly");
        const weeklyOption = Array.from(dropdown.options).find(option => option.text === "Weekly");
        if (monthlyOption) {
            monthlyOption.remove();
        }
    }, 100);
});
